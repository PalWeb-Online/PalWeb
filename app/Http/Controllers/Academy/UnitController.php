<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Http\Resources\LessonResource;
use App\Http\Resources\UnitResource;
use App\Models\Lesson;
use App\Models\Unit;
use App\Services\LessonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Inertia\Inertia;

class UnitController extends Controller
{
    public function index(): \Inertia\Response
    {
        $units = auth()->user()->isAdmin()
            ? Unit::all()
            : Unit::published()->get();

        return Inertia::render('Academy/Units/Index', [
            'section' => 'academy',
            'units' => UnitResource::collection($units),
        ]);
    }

    public function show(Unit $unit): \Inertia\Response
    {
        Gate::authorize('view', $unit);

        $lessons = auth()->user()->isAdmin()
            ? $unit->lessons
            : $unit->lessons()->published()->get();

        return Inertia::render('Academy/Units/Show', [
            'section' => 'academy',
            'unit' => new UnitResource($unit),
            'lessons' => LessonResource::collection($lessons),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $unit = Unit::create([
                'position' => $request->position ?? Unit::count() + 1,
                'title' => $request->title,
                'published' => $request->published,
            ]);

            foreach ($request->lessons as $lessonData) {
                Lesson::updateOrCreate(['id' => $lessonData['id']], [
                    'unit_id' => $unit->id,
                    'slug' => Str::uuid(),
                    'title' => $lessonData['title'],
                ]);
            }

            $unit->refresh();
            LessonService::reorderUnitLessons($unit);
        });

        session()->flash('notification',
            ['type' => 'success', 'message' => __('created', ['thing' => $unit->title])]);
        return to_route('lesson-planner.unit', $unit);
    }

    public function update(Request $request, Unit $unit): RedirectResponse
    {
        if (count($request->lessons) > 9) {
            session()->flash('notification',
                ['type' => 'error', 'message' => __('max_lessons_exceeded')]);
            return back();
        }

        DB::transaction(function () use ($request, $unit) {
            $unit->update([
                'position' => $request->position,
                'title' => $request->title,
                'published' => $request->published,
            ]);

            $existingIds = $unit->lessons()->pluck('id')->all();
            $incomingIds = collect($request->lessons ?? [])
                ->pluck('id')
                ->filter()
                ->all();
            $detachedIds = array_diff($existingIds, $incomingIds);

            if (! empty($detachedIds)) {
                Lesson::whereIn('id', $detachedIds)->get()->each(function (Lesson $lesson) {
                    $lesson->update([
                        'unit_id' => null,
                        'position' => 0,
                        'slug' => 'id'.$lesson->id,
                    ]);
                });
            }

            foreach ($request->lessons as $lessonData) {
                Lesson::updateOrCreate(
                    ['id' => $lessonData['id']],
                    [
                        'unit_id' => $unit->id,
                        'position' => $lessonData['position'],
                        'slug' => Str::uuid(),
                        'title' => $lessonData['title'],
                    ]
                );
            }

            $unit->refresh();
            LessonService::reorderUnitLessons($unit);
        });

        session()->flash('notification',
            ['type' => 'success', 'message' => __('updated', ['thing' => $unit->title])]);
        return to_route('lesson-planner.unit', $unit);
    }

    public function destroy(Unit $unit): RedirectResponse
    {
        DB::transaction(function () use ($unit) {
            $unit->lessons()->each(function (Lesson $lesson) {
                $lesson->update([
                    'position' => 0,
                    'slug' => 'id'.$lesson->id,
                ]);
            });
            $unit->delete();
            LessonService::reorderAllUnitsAndLessons();
        });

        session()->flash('notification',
            ['type' => 'success', 'message' => __('deleted', ['thing' => $unit->title])]);
        return to_route('lesson-planner.index');
    }
}
