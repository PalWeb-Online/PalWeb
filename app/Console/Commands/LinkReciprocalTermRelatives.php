<?php

namespace App\Console\Commands;

use App\Services\TermRelativeService;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LinkReciprocalTermRelatives extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'terms:link-reciprocal-relatives {--dry-run : Report links without updating rows}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link existing term_relative rows to their reciprocal rows.';

    public function __construct(private readonly TermRelativeService $termRelativeService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $stats = [
            'linked' => 0,
            'missing' => 0,
            'ambiguous' => 0,
            'mismatched' => 0,
            'missing_type' => 0,
        ];
        $reservedIds = [];

        DB::table('term_relative')
            ->whereNull('reciprocal_id')
            ->orderBy('id')
            ->chunkById(500, function ($rows) use (&$stats, &$reservedIds) {
                foreach ($rows as $row) {
                    $current = DB::table('term_relative')->where('id', $row->id)->first();

                    if (! $current || $current->reciprocal_id || isset($reservedIds[$current->id])) {
                        continue;
                    }

                    if ($current->type === null) {
                        $stats['missing_type']++;
                        $this->logAmbiguity('missing_type', $current, []);

                        continue;
                    }

                    $expectedTypes = $this->expectedReciprocalTypes($current->type);
                    $candidates = DB::table('term_relative')
                        ->where('term_id', $current->relative_id)
                        ->where('relative_id', $current->term_id)
                        ->where('id', '!=', $current->id)
                        ->whereNull('reciprocal_id')
                        ->orderBy('id');

                    if ($reservedIds !== []) {
                        $candidates->whereNotIn('id', array_keys($reservedIds));
                    }

                    $this->whereTypes($candidates, $expectedTypes);

                    $candidates = $candidates->get();

                    if ($candidates->count() === 1 || ($candidates->count() > 1 && $this->shouldLinkFirstCandidate($current))) {
                        $candidate = $candidates->first();

                        $this->linkRows($current, $candidate);
                        $reservedIds[$current->id] = true;
                        $reservedIds[$candidate->id] = true;

                        $stats['linked']++;

                        continue;
                    }

                    if ($candidates->count() > 1) {
                        $stats['ambiguous']++;
                        $this->logAmbiguity('ambiguous', $current, [
                            ...$this->expectedTypeContext($expectedTypes),
                            'candidate_ids' => $candidates->pluck('id')->all(),
                        ]);

                        continue;
                    }

                    $reverseRows = DB::table('term_relative')
                        ->where('term_id', $current->relative_id)
                        ->where('relative_id', $current->term_id)
                        ->where('id', '!=', $current->id)
                        ->get();

                    $compatibleReverseRows = $reverseRows
                        ->filter(fn ($reverseRow) => $this->typeMatches($reverseRow->type, $expectedTypes))
                        ->values();

                    if ($compatibleReverseRows->isNotEmpty()) {
                        $stats['missing']++;
                        $this->logAmbiguity('missing', $current, [
                            ...$this->expectedTypeContext($expectedTypes),
                            'reverse_rows' => $compatibleReverseRows
                                ->map(fn ($reverseRow) => [
                                    'id' => $reverseRow->id,
                                    'type' => $reverseRow->type,
                                    'reciprocal_id' => $reverseRow->reciprocal_id,
                                ])
                                ->all(),
                        ]);

                        continue;
                    }

                    if ($reverseRows->isNotEmpty()) {
                        $stats['mismatched']++;
                        $this->logAmbiguity('mismatched_type', $current, [
                            ...$this->expectedTypeContext($expectedTypes),
                            'reverse_rows' => $reverseRows
                                ->map(fn ($reverseRow) => [
                                    'id' => $reverseRow->id,
                                    'type' => $reverseRow->type,
                                    'reciprocal_id' => $reverseRow->reciprocal_id,
                                ])
                                ->all(),
                        ]);

                        continue;
                    }

                    $stats['missing']++;
                    $this->logAmbiguity('missing', $current, [
                        ...$this->expectedTypeContext($expectedTypes),
                    ]);
                }
            });

        $this->info(sprintf(
            'Finished linking term relatives. Linked: %d. Missing: %d. Ambiguous: %d. Mismatched: %d. Missing type: %d.',
            $stats['linked'],
            $stats['missing'],
            $stats['ambiguous'],
            $stats['mismatched'],
            $stats['missing_type'],
        ));

        return self::SUCCESS;
    }

    private function logAmbiguity(string $reason, object $row, array $context): void
    {
        $payload = [
            'reason' => $reason,
            'pivot_id' => $row->id,
            'term_id' => $row->term_id,
            'relative_id' => $row->relative_id,
            'type' => $row->type,
            ...$context,
        ];

        Log::warning('Unable to link reciprocal term relative.', $payload);
        $this->warn('Unable to link term_relative '.$row->id.': '.$reason);
    }

    private function linkRows(object $current, object $candidate): void
    {
        if ($this->option('dry-run')) {
            return;
        }

        $now = now();

        DB::table('term_relative')
            ->where('id', $current->id)
            ->update([
                'reciprocal_id' => $candidate->id,
                'updated_at' => $now,
            ]);

        DB::table('term_relative')
            ->where('id', $candidate->id)
            ->update([
                'reciprocal_id' => $current->id,
                'updated_at' => $now,
            ]);
    }

    private function expectedReciprocalTypes(?string $type): array
    {
        if (in_array($type, TermRelativeService::VALENCE_TYPES, true)) {
            return array_values(array_diff(TermRelativeService::VALENCE_TYPES, [$type]));
        }

        if ($type === 'source') {
            return TermRelativeService::DERIVATIVE_TYPES;
        }

        if (in_array($type, TermRelativeService::DERIVATIVE_TYPES, true)) {
            return ['source'];
        }

        return [$this->termRelativeService->reciprocalRelativeType($type)];
    }

    private function expectedTypeContext(array $types): array
    {
        if (count($types) === 1) {
            return ['expected_type' => $types[0]];
        }

        return ['expected_types' => $types];
    }

    private function shouldLinkFirstCandidate(object $row): bool
    {
        return in_array($row->type, ['synonym', 'antonym'], true);
    }

    private function typeMatches(?string $type, array $types): bool
    {
        return in_array($type, $types, true);
    }

    private function whereTypes(Builder $query, array $types): void
    {
        $nonNullTypes = array_values(array_filter($types, fn ($type) => $type !== null));
        $includesNull = in_array(null, $types, true);

        $query->where(function (Builder $query) use ($includesNull, $nonNullTypes) {
            if ($includesNull) {
                $query->whereNull('type');
            }

            if ($nonNullTypes !== []) {
                if ($includesNull) {
                    $query->orWhereIn('type', $nonNullTypes);

                    return;
                }

                $query->whereIn('type', $nonNullTypes);
            }
        });
    }
}
