<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpsertActivityRequest;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Throwable;

class ActivityController extends Controller
{
    public function show(Activity $activity): JsonResponse
    {
        Gate::authorize('view', $activity);

        $activity->load(['scores']);

        return response()->json([
            'activity' => new ActivityResource($activity),
        ]);
    }

    public function edit(Activity $activity): JsonResponse
    {
        Gate::authorize('view', $activity);

        $activity->load(['lesson']);

        return response()->json([
            'activity' => new ActivityResource($activity),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpsertActivityRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $lesson = Lesson::query()->findOrFail($validated['lesson_id']);

        $activity = DB::transaction(function () use ($validated, $lesson) {
            $activity = Activity::create([
                'title' => $validated['title'],
                'document' => $validated['document'],
                'published' => $validated['published'],
            ]);

            $lesson->update(['activity_id' => $activity->id]);

            return $activity;
        });

        return response()->json([
            'activity' => new ActivityResource($activity),
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpsertActivityRequest $request, Activity $activity): JsonResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $activity) {
            $activity->update([
                'title' => $validated['title'],
                'document' => $validated['document'],
                'published' => $validated['published'],
            ]);

            $activity->refresh();
        });

        return response()->json([
            'activity' => new ActivityResource($activity),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity): JsonResponse
    {
        try {
            Gate::authorize('delete', $activity);

            DB::transaction(function () use ($activity) {
                $activity->delete();
            });

            return response()->json([
                'success' => true,
            ]);

        } catch (Throwable $e) {
            Log::error('Failed to delete Activity.', [
                'activity_id' => $activity->id,
                'exception' => $e,
            ]);

            return $this->failureJsonResponse('Unable to delete Activity.', $e);
        }
    }

}
