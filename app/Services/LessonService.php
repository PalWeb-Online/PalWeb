<?php


namespace App\Services;

use App\Models\Unit;
use Illuminate\Support\Str;

class LessonService
{
    public static function reorderUnitLessons(Unit $unit): void
    {
        $lessons = $unit->lessons()
            ->orderBy('position')
            ->orderBy('id')
            ->get();

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
    }

    public static function reorderAllUnitsAndLessons(): void
    {
        $units = Unit::query()->orderBy('position')->orderBy('id')->get();

        if ($units->isEmpty()) {
            return;
        }

        $units->load(['lessons' => function ($query) {
            $query->orderBy('position')->orderBy('id');
        }]);

        $allLessons = $units
            ->flatMap(function (Unit $unit) {
                return $unit->lessons;
            })
            ->values();

        if ($allLessons->isEmpty()) {
            foreach ($units as $index => $unit) {
                $position = $index + 1;

                $unit->update([
                    'position' => $position,
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
            $position = $index + 1;

            $unit->update([
                'position' => $position,
            ]);
        }

        foreach ($units as $unit) {
            $lessons = $unit->lessons->sortBy('position')->sortBy('id')->values();

            foreach ($lessons as $index => $lesson) {
                $position = $index + 1;

                $lesson->update([
                    'position' => $position,
                    'slug'     => $unit->position.'0'.$position,
                ]);
            }
        }
    }
}
