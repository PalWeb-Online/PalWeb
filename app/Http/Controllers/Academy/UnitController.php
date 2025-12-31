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
        $lessons = Lesson::where('unit_id', null)->get();

        return Inertia::render('Academy/Units/Index', [
            'section' => 'academy',
            'units' => UnitResource::collection(Unit::all()),
            'lessons' => LessonResource::collection($lessons),
        ]);
    }

    public function show(Unit $unit): \Inertia\Response
    {
        Gate::authorize('view', $unit);

        return Inertia::render('Academy/Units/Show', [
            'section' => 'academy',
            'unit' => new UnitResource($unit),
            'lessons' => LessonResource::collection($unit->lessons),
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

//            todo: I don't think this ever happens, because the Unit is created first before adding Lessons
//            foreach ($request->lessons as $lessonData) {
//                Lesson::updateOrCreate(['id' => $lessonData['id']], [
//                    'unit_id' => $unit->id,
//                    'global_position' => Str::uuid(),
//                    'title' => $lessonData['title'],
//                ]);
//            }
//            $unit->refresh();

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
                        'unit_position' => null,
                        'global_position' => 'ex'.$lesson->id,
                    ]);
                });
            }

            foreach ($request->lessons as $lessonData) {
                Lesson::updateOrCreate(
                    ['id' => $lessonData['id']],
                    [
                        'unit_id' => $unit->id,
                        'unit_position' => $lessonData['unit_position'],
                        'global_position' => 'tmp-'.Str::uuid(),
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
