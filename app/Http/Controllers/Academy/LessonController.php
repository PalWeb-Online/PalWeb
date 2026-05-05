<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpsertLessonRequest;
use App\Http\Resources\LessonResource;
use App\Http\Resources\UnitResource;
use App\Models\Deck;
use App\Models\Dialog;
use App\Models\Lesson;
use App\Models\Unit;
use App\Services\LessonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Inertia\Inertia;

class LessonController extends Controller
{
    public function show(Lesson $lesson): \Inertia\Response
    {
        Gate::authorize('view', $lesson);

        $lesson->load([
            'unit',
            'deck.terms' => fn ($q) => $q->withUserCard(),
            'deck.terms.pronunciations',
            'deck.scores',
            'activity.scores',
            'dialog.sentences'
        ]);

        return Inertia::render('Academy/Lessons/Show', [
            'section' => 'academy',
            'unit' => $lesson->unit ? new UnitResource($lesson->unit) : null,
            'lesson' => new LessonResource($lesson),
        ]);
    }

    public function store(UpsertLessonRequest $request): RedirectResponse
    {
//        todo: this method is hit if & only if the Lesson is created via the `lesson-planner.unit-lesson` route;
//         if it's created in the `lesson-planner.unit` route, the UnitController store() method is hit.
        $lesson = DB::transaction(function () use ($request) {
            $unit = $request->unit_id ? Unit::find($request->unit_id) : null;

            $request->merge([
                'unit_position' => null,
                'global_position' => 'tmp-'.Str::uuid()
            ]);

            $lesson = Lesson::create($request->all());

            if ($unit) {
                LessonService::reorderUnitLessons($unit);

//            todo: we haven't yet created a way to create a standalone Lesson
//        } else {
//            $lesson->update([
//                'unit_position' => null,
//                'global_position' => 'ex'.$lesson->id
//            ]);
            }

            return $lesson;
        });

        session()->flash('notification',
            ['type' => 'success', 'message' => __('created', ['thing' => $lesson->title])]);
        return to_route('lesson-planner.lesson', $lesson);
    }

    public function update(UpsertLessonRequest $request, Lesson $lesson): RedirectResponse
    {
        DB::transaction(function () use ($request, $lesson) {
            $oldUnit = $lesson->unit;

            if (! $request->unit_id) {
                $request->merge([
                    'unit_position' => null,
                    'global_position' => 'ex'.$lesson->id,
                ]);

            } else {
                $incomingUnit = Unit::find($request->unit_id);

                if ($oldUnit && $incomingUnit && $oldUnit->id !== $incomingUnit->id) {
                    $request->merge([
                        'unit_position' => null,
                        'global_position' => 'tmp-'.Str::uuid(),
                    ]);
                }
            }

            $lesson->update($request->all());
            $lesson->refresh();

            $newUnit = $lesson->unit;

            if ($oldUnit && (! $newUnit || $oldUnit->id !== $newUnit->id)) {
                LessonService::reorderUnitLessons($oldUnit);

            } elseif ($newUnit && $oldUnit?->id !== $newUnit->id) {
                LessonService::reorderUnitLessons($newUnit);
            }
        });

        $lesson->refresh();

        session()->flash('notification',
            ['type' => 'success', 'message' => __('updated', ['thing' => $lesson->title])]);
        return to_route('lesson-planner.lesson', $lesson);
    }

    public function destroy(Lesson $lesson): RedirectResponse
    {
        DB::transaction(function () use ($lesson) {
            $unit = $lesson->unit;
            $lesson->delete();

            if ($unit) {
                LessonService::reorderUnitLessons($unit);
            }
        });

        session()->flash('notification',
            ['type' => 'success', 'message' => __('deleted', ['thing' => $lesson->title])]);
        return to_route('lesson-planner.index');
    }
}
