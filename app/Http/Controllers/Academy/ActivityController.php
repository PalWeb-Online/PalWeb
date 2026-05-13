<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpsertActivityRequest;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        return Inertia::render('Academy/Activities/Show', [
            'section' => 'academy',
            'activityId' => $activity->id,
        ]);
    }

    public function activity(Activity $activity): \Inertia\Response
    {
        Gate::authorize('view', $activity);

        return Inertia::render('Academy/Activities/Activity', [
            'section' => 'academy',
            'activityId' => $activity->id,
        ]);
    }

    public function fetch(Request $request, Activity $activity): JsonResponse
    {
        Gate::authorize('view', $activity);

        $activity->load($this->resolveActivityIncludes($this->requestedActivityIncludes($request)));

        return response()->json([
            'activity' => new ActivityResource($activity),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpsertActivityRequest $request): RedirectResponse|JsonResponse
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

        if ($request->expectsJson()) {
            return response()->json([
                'activity' => new ActivityResource($activity),
                'message' => 'Activity created successfully.',
            ], 201);
        }

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

            $activity->refresh();
        });

        if ($request->expectsJson()) {
            return response()->json([
                'activity' => new ActivityResource($activity),
                'message' => 'Activity updated successfully.',
            ]);
        }

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

    private function requestedActivityIncludes(Request $request): array
    {
//        $profileIncludes = self::ACTIVITY_INCLUDE_PROFILES[$request->query('profile')] ?? [];
        $profileIncludes = [];

        $explicitIncludes = collect(explode(',', (string) $request->query('include')))
            ->map(fn (string $include) => trim($include))
            ->filter()
            ->all();

        return collect($profileIncludes)
            ->merge($explicitIncludes)
            ->unique()
            ->values()
            ->all();
    }

    private function resolveActivityIncludes(array $includes): array
    {
        $allowedIncludes = [
            'scores' => 'scores',
        ];

        $loads = [];

        foreach ($includes as $include) {
            if (! array_key_exists($include, $allowedIncludes)) {
                continue;
            }

            $load = $allowedIncludes[$include];

            if (is_array($load)) {
                foreach ($load as $relation => $constraint) {
                    if (is_int($relation)) {
                        $loads[] = $constraint;
                    } else {
                        $loads[$relation] = $constraint;
                    }
                }

                continue;
            }

            $loads[] = $load;
        }

        return $loads;
    }
}
