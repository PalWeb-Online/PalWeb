<?php


namespace App\Services;

use App\Models\Lesson;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LessonService
{
    public static function reorderUnitLessons(Unit $unit): void
    {
        $lessons = $unit->lessons;

        if ($lessons->isEmpty()) {
            return;
        }

        foreach ($lessons as $lesson) {
            $lesson->update([
                'slug' => 'tmp-'.$lesson->id.'-'.Str::uuid(),
            ]);
        }

        foreach ($lessons as $index => $lesson) {
            $position = $index + 1;

            $lesson->update([
                'position' => $position,
                'slug' => $unit->position.'0'.$position,
            ]);
        }

        User::role(['student', 'admin'])->chunk(100, function ($users) {
            foreach ($users as $user) {
                self::syncUserProgress($user);
            }
        });
    }

    public static function reorderAllUnitsAndLessons(): void
    {
        $units = Unit::all();

        if ($units->isEmpty()) {
            return;
        }

        $allLessons = $units
            ->flatMap(function (Unit $unit) {
                return $unit->lessons;
            })
            ->values();

        if ($allLessons->isEmpty()) {
            foreach ($units as $index => $unit) {
                $unit->update([
                    'position' => $index + 1,
                ]);
            }

            return;
        }

        foreach ($allLessons as $lesson) {
            $lesson->update([
                'slug' => 'tmp-'.$lesson->id.'-'.Str::uuid(),
            ]);
        }

        foreach ($units as $index => $unit) {
            $unit->update([
                'position' => $index + 1,
            ]);
        }

        foreach ($units as $unit) {
            $lessons = $unit->lessons;

            foreach ($lessons as $index => $lesson) {
                $position = $index + 1;

                $lesson->update([
                    'position' => $position,
                    'slug' => $unit->position.'0'.$position,
                ]);
            }
        }

        User::role('student')->chunk(100, function ($users) {
            foreach ($users as $user) {
                self::syncUserProgress($user);
            }
        });
    }

    public static function canUnlock(User $user, Lesson $lesson, ?array $completedIds = null): bool
    {
        if ($completedIds === null) {
            $progress = $user->getLessonProgress();
            $completedIds = collect($progress)
                ->filter(fn($p) => $p['completed'])
                ->keys()
                ->toArray();
        }

        $conditions = $lesson->unlock_conditions ?? [];

        if (empty($conditions)) {
            if ($lesson->group !== 'main') {
                return false;
            }

            $previousLesson = Lesson::where('group', 'main')
                ->where('slug', '<', $lesson->slug)
                ->orderByDesc('slug')
                ->first();

            if (! $previousLesson) {
                return true;
            }

            return in_array($previousLesson->id, $completedIds);
        }

        foreach ($conditions as $condition) {
            $type = $condition['type'] ?? null;
            $value = $condition['value'] ?? null;

            switch ($type) {
                case 'after_lesson_id':
                    if (!in_array($value, $completedIds)) return false;
                    break;

                case 'after_lesson_position':
                    $requiredIds = Lesson::where('group', 'main')
                        ->where('slug', '<=', $value)
                        ->pluck('id');

                    if (empty($requiredIds)) return false;
                    if (count(array_intersect($requiredIds, $completedIds)) < count($requiredIds)) return false;
                    break;

                case 'after_unit_id':
                    $unit = Unit::find($value);
                    if (! $unit) return false;

                    $requiredIds = $unit->lessons()->pluck('id');
                    if (empty($requiredIds)) return true;
                    if (count(array_intersect($requiredIds, $completedIds)) < count($requiredIds)) return false;
                    break;

                case 'after_unit_position':
                    $units = Unit::where('position', '<=', $value)->pluck('id');
                    $requiredIds = Lesson::whereIn('unit_id', $units)->pluck('id')->toArray();

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
        $lessons = Lesson::where('published', true)->get();

        do {
            $newlyUnlockedCount = 0;

            $progress = $user->getLessonProgress();
            $unlockedIds = array_keys($progress);
            $completedIds = collect($progress)->filter(fn($p) => $p['completed'])->keys()->toArray();

            foreach ($lessons as $lesson) {
                if (in_array($lesson->id, $unlockedIds)) {
                    continue;
                }

                if (self::canUnlock($user, $lesson, $completedIds)) {
                    $user->lessons()->syncWithoutDetaching($lesson->id);
                    $newlyUnlocked->push($lesson);
                    $newlyUnlockedCount++;

                    $unlockedIds[] = $lesson->id;
                }
            }

            if ($newlyUnlockedCount > 0) {
                $user->forgetLessonProgressCache();
            }

        } while ($newlyUnlockedCount > 0);

        return $newlyUnlocked;
    }
}
