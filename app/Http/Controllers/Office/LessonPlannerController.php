<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Http\Resources\LessonResource;
use App\Http\Resources\UnitResource;
use App\Models\Lesson;
use App\Models\Unit;
use App\Services\LessonService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LessonPlannerController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Office/LessonPlanner/Course', [
            'section' => 'office',
            'units' => UnitResource::collection(Unit::all()),
            'lessons' => LessonResource::collection(Lesson::where('unit_id', null)->get()),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'units' => ['required', 'array'],
            'units.*.id' => ['nullable', 'integer', 'exists:units,id'],
            'units.*.title' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['units'] as $index => $unitData) {
                $position = $index + 1;

                if (!empty($unitData['id'])) {
                    Unit::whereKey($unitData['id'])->update([
                        'title' => $unitData['title'],
                        'position' => $position,
                    ]);

                } else {
                    Unit::create([
                        'title' => $unitData['title'],
                        'position' => $position,
                    ]);
                }
            }

            LessonService::reorderAllUnitsAndLessons();
        });

        session()->flash('notification',
            ['type' => 'success', 'message' => __('updated', ['thing' => 'the Course'])]);
        return to_route('lesson-planner.index');
    }

    public function unit(?Unit $unit = null): \Inertia\Response
    {
        return Inertia::render('Office/LessonPlanner/Unit', [
            'section' => 'office',
            'unit' => $unit ? new UnitResource($unit) : null,
        ]);
    }

    public function unitLesson(Unit $unit): \Inertia\Response
    {
        return Inertia::render('Office/LessonPlanner/Lesson', [
            'section' => 'office',
            'lesson' => [
                'unit' => [
                    'id' => $unit->id,
                    'title' => $unit->title,
                ],
            ],
        ]);
    }

    public function lesson(?Lesson $lesson = null): \Inertia\Response
    {
        return Inertia::render('Office/LessonPlanner/Lesson', [
            'section' => 'office',
            'lesson' => $lesson ? new LessonResource($lesson) : null,
        ]);
    }
}
