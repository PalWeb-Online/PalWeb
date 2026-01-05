<?php


namespace App\Services;

use App\Jobs\SyncAllUsersProgress;
use App\Models\Lesson;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LessonService
{
    public static function reorderUnitLessons(Unit $unit): void
    {
        $lessons = $unit->lessons()
            ->withoutGlobalScopes()
            ->get()
            ->sortBy(fn($lesson) => $lesson->unit_position ?? 999);

        if (self::applyNewOrder($unit->position, $lessons)) {
            SyncAllUsersProgress::dispatch()->afterCommit();
        }
    }

    public static function reorderAllUnitsAndLessons(): void
    {
        $anyChanges = false;

        $units = Unit::withoutGlobalScopes()->orderBy('position')->get();

        foreach ($units as $index => $unit) {
            $unit->update(['position' => $index + 1]);

            $lessons = $unit->lessons()->withoutGlobalScopes()->get();

            if (self::applyNewOrder($unit->position, $lessons)) {
                $anyChanges = true;
            }
        }

        if ($anyChanges) {
            SyncAllUsersProgress::dispatch()->afterCommit();
        }
    }

    private static function applyNewOrder(int $unitPosition, Collection $lessons): bool
    {
        if ($lessons->isEmpty()) {
            return false;
        }

        Lesson::withoutEvents(function () use ($lessons, $unitPosition) {
            foreach ($lessons as $lesson) {
                $lesson->update(['global_position' => 'tmp-'.Str::uuid()]);
            }

            foreach ($lessons->values() as $index => $lesson) {
                $pos = $index + 1;
                $lesson->update([
                    'unit_position' => $pos,
                    'global_position' => $unitPosition.'0'.$pos,
                ]);
            }
        });

        return true;
    }

    public static function canUnlock(Lesson $lesson, array $completedIds): bool
    {
        if ((string) $lesson->global_position === '101') {
            return true;
        }

        $conditions = $lesson->unlock_conditions ?? [];

        if (empty($conditions)) {
            if ($lesson->group !== 'main') return false;

            $previousLesson = Lesson::withoutGlobalScopes()
                ->where('group', 'main')
                ->where('global_position', '<', $lesson->global_position)
                ->orderByDesc('global_position')
                ->first();

            if (! $previousLesson) return true;

            return in_array($previousLesson->id, $completedIds);
        }

        foreach ($conditions as $condition) {
            $type = $condition['type'] ?? null;
            $value = $condition['value'] ?? null;

            switch ($type) {
                case 'after_lesson_id':
                    if (! in_array($value, $completedIds)) return false;
                    break;

                case 'after_lesson_position':
                    $requiredIds = Lesson::withoutGlobalScopes()
                        ->where('group', 'main')
                        ->where('global_position', '<=', $value)
                        ->pluck('id');

                    if (empty($requiredIds)) return false;
                    if (count(array_intersect($requiredIds, $completedIds)) < count($requiredIds)) return false;
                    break;

                case 'after_unit_id':
                    $unit = Unit::withoutGlobalScopes()->find($value);
                    if (! $unit) return false;

                    $requiredIds = $unit->withoutGlobalScopes()->lessons()->pluck('id');
                    if (empty($requiredIds)) return true;

                    if (count(array_intersect($requiredIds, $completedIds)) < count($requiredIds)) return false;
                    break;

                case 'after_unit_position':
                    $units = Unit::withoutGlobalScopes()
                        ->where('position', '<=', $value)
                        ->pluck('id');

                    $requiredIds = Lesson::withoutGlobalScopes()
                        ->whereIn('unit_id', $units)
                        ->pluck('id')
                        ->toArray();

                    if (empty($requiredIds)) return true;
                    if (count(array_intersect($requiredIds, $completedIds)) < count($requiredIds)) return false;
                    break;

                default:
                    return false;
            }
        }

        return true;
    }

    public static function syncUserProgress(User $user): Collection
    {
        if (! $user->isStudent() && ! $user->isAdmin()) {
            return collect();
        }

        $newlyUnlocked = collect();
        $publishedLessons = Lesson::withoutGlobalScopes()->where('published', true)->get();

        $progress = $user->getLessonProgress();
        $unlockedIds = array_keys($progress);

        $completedIds = [];
        foreach($progress as $id => $data) {
            if ($data['completed']) $completedIds[] = $id;
        }

        $idsToAttach = [];

        foreach ($publishedLessons as $lesson) {
            if (in_array($lesson->id, $unlockedIds)) continue;

            if (self::canUnlock($lesson, $completedIds)) {
                $newlyUnlocked->push($lesson);
                $idsToAttach[] = $lesson->id;
                $unlockedIds[] = $lesson->id;
            }
        }

        if (!empty($idsToAttach)) {
            $user->lessons()->syncWithoutDetaching($idsToAttach);
            $user->forgetLessonProgressCache();
        }

        return $newlyUnlocked;
    }
}
