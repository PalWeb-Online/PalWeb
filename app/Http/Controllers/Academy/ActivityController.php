<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpsertActivityRequest;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ActivityController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Activity $activity): \Inertia\Response
    {
        Gate::authorize('view', $activity);

        $activity->load(['scores']);

        return Inertia::render('Academy/Activities/Show', [
            'section' => 'academy',
            'activity' => new ActivityResource($activity),
        ]);
    }

    public function activity(Activity $activity): \Inertia\Response
    {
        Gate::authorize('view', $activity);

        $activity->load(['scores']);

        return Inertia::render('Academy/Activities/Activity', [
            'section' => 'academy',
            'activity' => new ActivityResource($activity),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpsertActivityRequest $request)
    {
        $validated = $request->validated();
        $lesson = Lesson::query()->findOrFail($validated['lesson_id']);

        DB::transaction(function () use ($validated, $lesson) {
            $activity = Activity::create([
                'title' => $validated['title'],
                'document' => $validated['document'],
                'published' => $validated['published'],
            ]);

            $lesson->update(['activity_id' => $activity->id]);
        });

        session()->flash('notification',
            ['type' => 'success', 'message' => __('created', ['thing' => 'Activity'])]);
        return to_route('lesson-planner.lesson-activity', $lesson);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpsertActivityRequest $request, Activity $activity)
    {
        $validated = $request->validated();
        $lesson = $activity->lesson;

        DB::transaction(function () use ($validated, $activity) {
            $activity->update([
                'title' => $validated['title'],
                'document' => $validated['document'],
                'published' => $validated['published'],
            ]);
        });

        session()->flash('notification',
            ['type' => 'success', 'message' => __('updated', ['thing' => 'Activity'])]);
        return to_route('lesson-planner.lesson-activity', $lesson);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        $lesson = $activity->lesson;
        $activity->delete();

        session()->flash('notification',
            ['type' => 'success', 'message' => __('deleted', ['thing' => 'Activity'])]);
        return to_route('lesson-planner.lesson', $lesson);
    }
}
