<?php


namespace App\Services;

use App\Models\Unit;
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
                    'slug'     => $unit->position.'0'.$position,
                ]);
            }
        }
    }
}
