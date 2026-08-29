<?php

namespace App\Services;

use App\Models\Term;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TermRelativeService
{
    public const GLOSS_TYPES = ['synonym', 'antonym', 'isPatient', 'noPatient', 'hasObject'];

    public const VALENCE_TYPES = ['isPatient', 'noPatient', 'hasObject'];

    public const DERIVATIVE_TYPES = ['ap', 'pp', 'vn'];

    public const SOURCE_TYPES = ['source'];

    public function sync(Term $term, array $relatives): void
    {
        $term->load('relatives');

        $existingPivotIds = $term->relatives
            ->pluck('pivot.id')
            ->filter()
            ->values();

        $validRelatives = $this->resolveRelatives($relatives);

        $this->validateNoDuplicateRelatives($validRelatives);

        $deletedRelatives = $term->relatives
            ->filter(fn (Term $relative) => ! in_array($relative->pivot->id, $validRelatives->pluck('pivot_id')->all(), true));

        foreach ($deletedRelatives as $deletedRelative) {
            $this->deletePair((int) $deletedRelative->pivot->id);
        }

        foreach ($validRelatives as $relative) {
            $relativeTerm = $relative['term'];
            $type = $relative['type'];
            $glossId = $relative['gloss_id'];
            $pivotId = $relative['pivot_id'];

            if ($pivotId && $existingPivotIds->contains($pivotId)) {
                $this->updatePair($term, $relativeTerm, $pivotId, $type, $glossId, $relative['index']);
                continue;
            }

            $this->createPair($term, $relativeTerm, $type, $glossId);
        }
    }

    public function reciprocalRelativeType(?string $type): ?string
    {
        return match ($type) {
            'component' => 'descendant',
            'descendant' => 'component',
            'ap', 'pp', 'vn' => 'source',
            'source', 'isPatient', 'noPatient', 'hasObject' => null,
            default => $type,
        };
    }

    public function requiresGloss(?string $type): bool
    {
        return in_array($type, self::GLOSS_TYPES, true);
    }

    public function allowedTypeOptions(?string $type): array
    {
        if (! $type) {
            return [
                'variant',
                'reference',
                'component',
                'descendant',
                'source',
                ...self::DERIVATIVE_TYPES,
                'synonym',
                'antonym',
                ...self::VALENCE_TYPES,
            ];
        }

        if (in_array($type, self::SOURCE_TYPES, true)) {
            return [$type];
        }

        if (in_array($type, ['component', 'descendant'], true)) {
            return [$type];
        }

        if (in_array($type, self::DERIVATIVE_TYPES, true)) {
            return self::DERIVATIVE_TYPES;
        }

        if (in_array($type, self::VALENCE_TYPES, true)) {
            return self::VALENCE_TYPES;
        }

        if (in_array($type, ['synonym', 'antonym'], true)) {
            return ['synonym', 'antonym'];
        }

        if (in_array($type, ['variant', 'reference'], true)) {
            return ['variant', 'reference'];
        }

        return [$type];
    }

    private function createPair(Term $term, Term $relativeTerm, string $type, ?int $glossId): int
    {
        $this->validateUniqueRelative($term->id, $relativeTerm->id, $type, $glossId);

        $reciprocalType = $this->reciprocalRelativeType($type);
        $reciprocalId = $this->shouldReuseReciprocal($reciprocalType)
            ? $this->reusableReciprocalId($relativeTerm->id, $term->id, $reciprocalType)
            : null;

        $now = now();

        $pivotId = DB::table('term_relative')->insertGetId([
            'term_id' => $term->id,
            'relative_id' => $relativeTerm->id,
            'type' => $type,
            'gloss_id' => $glossId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        if ($reciprocalId) {
            DB::table('term_relative')
                ->where('id', $reciprocalId)
                ->update([
                    'reciprocal_id' => $pivotId,
                    'updated_at' => $now,
                ]);
        } else {
            $reciprocalId = DB::table('term_relative')->insertGetId([
                'term_id' => $relativeTerm->id,
                'relative_id' => $term->id,
                'type' => $reciprocalType,
                'gloss_id' => null,
                'reciprocal_id' => $pivotId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        DB::table('term_relative')
            ->where('id', $pivotId)
            ->update([
                'reciprocal_id' => $reciprocalId,
                'updated_at' => $now,
            ]);

        return $pivotId;
    }

    private function updatePair(
        Term $term,
        Term $relativeTerm,
        int $pivotId,
        string $type,
        ?int $glossId,
        int $index,
    ): void {
        $pivot = DB::table('term_relative')
            ->where('id', $pivotId)
            ->where('term_id', $term->id)
            ->first();

        if (! $pivot) {
            return;
        }

        if ((int) $pivot->relative_id !== $relativeTerm->id) {
            throw ValidationException::withMessages([
                "relatives.{$index}.slug" => 'Existing relative relationships cannot be pointed at another term. Delete it and add a new relationship instead.',
            ]);
        }

        if (! in_array($type, $this->allowedTypeOptionsForPivot($pivot), true)) {
            throw ValidationException::withMessages([
                "relatives.{$index}.type" => 'This relative relationship type cannot be changed to the selected type. Delete it and add a new relationship instead.',
            ]);
        }

        $this->validateUniqueRelative($term->id, $relativeTerm->id, $type, $glossId, $pivotId, $index);

        $now = now();
        $reciprocalId = $this->resolveReciprocalId($term, $relativeTerm, $pivot, $type, $index);

        DB::table('term_relative')
            ->where('id', $pivotId)
            ->update([
                'type' => $type,
                'gloss_id' => $glossId,
                'reciprocal_id' => $reciprocalId,
                'updated_at' => $now,
            ]);

        if ($this->shouldSyncReciprocalType($pivot)) {
            $reciprocalType = $this->reciprocalRelativeType($type);

            DB::table('term_relative')
                ->where('id', $reciprocalId)
                ->update([
                    'type' => $reciprocalType,
                    'gloss_id' => $this->requiresGloss($reciprocalType) ? DB::raw('gloss_id') : null,
                    'updated_at' => $now,
                ]);
        }
    }

    private function createMissingReciprocal(Term $term, Term $relativeTerm, int $pivotId, ?string $type): int
    {
        $now = now();

        $reciprocalId = DB::table('term_relative')->insertGetId([
            'term_id' => $relativeTerm->id,
            'relative_id' => $term->id,
            'type' => $this->reciprocalRelativeType($type),
            'gloss_id' => null,
            'reciprocal_id' => $pivotId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return $reciprocalId;
    }

    private function resolveReciprocalId(
        Term $term,
        Term $relativeTerm,
        object $pivot,
        string $type,
        int $index,
    ): int {
        if ($pivot->reciprocal_id && DB::table('term_relative')->where('id', $pivot->reciprocal_id)->exists()) {
            return (int) $pivot->reciprocal_id;
        }

        $candidates = DB::table('term_relative')
            ->where('term_id', $relativeTerm->id)
            ->where('relative_id', $term->id)
            ->where('id', '!=', $pivot->id)
            ->whereNull('reciprocal_id');

        $this->whereType($candidates, $this->reciprocalRelativeType($pivot->type));

        $candidates = $candidates->get();

        if ($candidates->count() === 1) {
            $reciprocalId = (int) $candidates->first()->id;
            $now = now();

            DB::table('term_relative')
                ->where('id', $reciprocalId)
                ->update([
                    'reciprocal_id' => $pivot->id,
                    'updated_at' => $now,
                ]);

            return $reciprocalId;
        }

        if ($candidates->count() > 1) {
            throw ValidationException::withMessages([
                "relatives.{$index}.type" => 'This relative relationship has multiple possible reciprocal rows. Link the existing rows or clean up the duplicates before editing it.',
            ]);
        }

        $reciprocalSeedType = in_array($pivot->type, [...self::SOURCE_TYPES, ...self::VALENCE_TYPES], true)
            ? null
            : $type;

        $reciprocalType = $this->reciprocalRelativeType($reciprocalSeedType);

        if ($this->shouldReuseReciprocal($reciprocalType)) {
            $reusableReciprocalId = $this->reusableReciprocalId($relativeTerm->id, $term->id, $reciprocalType);

            if ($reusableReciprocalId) {
                DB::table('term_relative')
                    ->where('id', $reusableReciprocalId)
                    ->update([
                        'reciprocal_id' => $pivot->id,
                        'updated_at' => now(),
                    ]);

                return $reusableReciprocalId;
            }
        }

        return $this->createMissingReciprocal($term, $relativeTerm, (int) $pivot->id, $reciprocalSeedType);
    }

    private function resolveRelatives(array $relatives): Collection
    {
        return collect($relatives)
            ->map(function (array $relative, int $index) {
                $relativeTerm = Term::firstWhere('slug', $relative['slug']);

                if (! $relativeTerm) {
                    return null;
                }

                $type = $relative['type'] ?? null;

                return [
                    'index' => $index,
                    'term' => $relativeTerm,
                    'relative_id' => $relativeTerm->id,
                    'type' => $type,
                    'gloss_id' => $this->requiresGloss($type) ? ($relative['gloss_id'] ?? null) : null,
                    'pivot_id' => isset($relative['pivot_id']) ? (int) $relative['pivot_id'] : null,
                ];
            })
            ->filter()
            ->values();
    }

    private function validateNoDuplicateRelatives(Collection $relatives): void
    {
        $seen = [];

        foreach ($relatives as $relative) {
            if (! $relative['type']) {
                continue;
            }

            if ($this->requiresGloss($relative['type']) && ! $relative['gloss_id']) {
                continue;
            }

            $key = implode(':', [
                $relative['gloss_id'],
                $relative['relative_id'],
                $relative['type'],
            ]);

            if (isset($seen[$key])) {
                throw ValidationException::withMessages([
                    "relatives.{$relative['index']}.type" => 'This term already has this relative with the same type and gloss.',
                ]);
            }

            $seen[$key] = true;
        }
    }

    private function validateUniqueRelative(
        int $termId,
        int $relativeId,
        ?string $type,
        ?int $glossId,
        ?int $exceptPivotId = null,
        ?int $index = null,
    ): void {
        if (! $type) {
            return;
        }

        if ($this->requiresGloss($type) && ! $glossId) {
            return;
        }

        $exists = DB::table('term_relative')
            ->where('term_id', $termId)
            ->where('gloss_id', $glossId)
            ->where('relative_id', $relativeId)
            ->where('type', $type)
            ->when($exceptPivotId, fn ($query) => $query->where('id', '!=', $exceptPivotId))
            ->exists();

        if (! $exists) {
            return;
        }

        $field = $index === null
            ? 'relatives'
            : "relatives.{$index}.type";

        throw ValidationException::withMessages([
            $field => 'This term already has this relative with the same type and gloss.',
        ]);
    }

    private function allowedTypeOptionsForPivot(object $pivot): array
    {
        if ($pivot->type !== null) {
            return $this->allowedTypeOptions($pivot->type);
        }

        $reciprocalType = $this->reciprocalType($pivot);

        if ($reciprocalType === 'source') {
            return self::DERIVATIVE_TYPES;
        }

        if (in_array($reciprocalType, self::VALENCE_TYPES, true)) {
            return self::VALENCE_TYPES;
        }

        return $this->allowedTypeOptions(null);
    }

    private function shouldSyncReciprocalType(object $pivot): bool
    {
        if (in_array($pivot->type, [...self::VALENCE_TYPES, ...self::DERIVATIVE_TYPES], true)) {
            return false;
        }

        if ($pivot->type === null && in_array($this->reciprocalType($pivot), ['source', ...self::VALENCE_TYPES], true)) {
            return false;
        }

        return true;
    }

    private function reciprocalType(object $pivot): ?string
    {
        if (! $pivot->reciprocal_id) {
            return null;
        }

        return DB::table('term_relative')
            ->where('id', $pivot->reciprocal_id)
            ->value('type');
    }

    private function shouldReuseReciprocal(?string $type): bool
    {
        return $type === null || ! $this->requiresGloss($type);
    }

    private function reusableReciprocalId(int $termId, int $relativeId, ?string $type): ?int
    {
        $matches = DB::table('term_relative')
            ->where('term_id', $termId)
            ->where('relative_id', $relativeId)
            ->whereNull('gloss_id');

        $this->whereType($matches, $type);

        $matches = $matches->get();

        if ($matches->isEmpty()) {
            return null;
        }

        $unlinked = $matches->whereNull('reciprocal_id');

        if ($matches->count() === 1 && $unlinked->count() === 1) {
            return (int) $unlinked->first()->id;
        }

        throw ValidationException::withMessages([
            'relatives' => 'Creating this relative relationship would create or reuse an ambiguous duplicate reciprocal row. Run the reciprocal linker or clean up the duplicate rows first.',
        ]);
    }

    private function whereType(\Illuminate\Database\Query\Builder $query, ?string $type): void
    {
        if ($type === null) {
            $query->whereNull('type');

            return;
        }

        $query->where('type', $type);
    }

    private function deletePair(int $pivotId): void
    {
        $pivot = DB::table('term_relative')->where('id', $pivotId)->first();

        if (! $pivot) {
            return;
        }

        if ($pivot->reciprocal_id) {
            DB::table('term_relative')->where('id', $pivot->reciprocal_id)->delete();
        }

        DB::table('term_relative')->where('reciprocal_id', $pivotId)->delete();

        DB::table('term_relative')->where('id', $pivotId)->delete();
    }
}
