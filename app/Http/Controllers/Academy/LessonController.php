<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpsertLessonRequest;
use App\Http\Resources\LessonResource;
use App\Http\Resources\UnitResource;
use App\Models\Lesson;
use App\Models\Unit;
use App\Services\LessonService;
use App\Services\TermService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Throwable;

class LessonController extends Controller
{
    public function __construct(
        protected TermService $termService
    ) {
    }

    public function show(Lesson $lesson): \Inertia\Response
    {
        Gate::authorize('view', $lesson);

        return Inertia::render('Academy/Lessons/Show', [
            'section' => 'academy',
            'lessonId' => $lesson->id,
        ]);
    }

    public function fetch(Request $request, Lesson $lesson): JsonResponse
    {
        Gate::authorize('view', $lesson);

        $includes = collect(explode(',', (string) $request->query('include')))
            ->map(fn (string $include) => trim($include))
            ->filter()
            ->values();

        if ($includes->contains('show')) {
            $lesson->load([
                'unit',
                'deck.scores',
                'activity.scores',
                'dialog.sentences'
            ]);

        } else {
            $lesson->load([
                'unit',
                'deck',
                'activity',
                'dialog',
            ]);
        }

        return response()->json([
            'lesson' => new LessonResource($lesson),
            'unit' => $includes->contains('show') ? new UnitResource($lesson->unit) : [],
        ]);
    }

    public function store(UpsertLessonRequest $request): JsonResponse
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

        $lesson->load([
            'unit',
            'deck',
            'activity',
            'dialog',
        ]);

        return response()->json([
            'lesson' => new LessonResource($lesson),
        ], 201);
    }

    public function update(UpsertLessonRequest $request, Lesson $lesson): JsonResponse
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

        $lesson->refresh()->load([
            'unit',
            'deck',
            'activity',
            'dialog',
        ]);

        return response()->json([
            'lesson' => new LessonResource($lesson),
        ]);
    }

    public function destroy(Lesson $lesson): JsonResponse
    {
        try {
            Gate::authorize('delete', $lesson);

            DB::transaction(function () use ($lesson) {
                $unit = $lesson->unit;
                $lesson->delete();

                if ($unit) {
                    LessonService::reorderUnitLessons($unit);
                }
            });

            return response()->json([
                'success' => true,
            ]);

        } catch (Throwable $e) {
            Log::error('Failed to delete Lesson.', [
                'lesson_id' => $lesson->id,
                'exception' => $e,
            ]);

            return $this->failureJsonResponse('Unable to delete Lesson.', $e);
        }
    }
}
