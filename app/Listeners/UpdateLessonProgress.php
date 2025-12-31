<?php

namespace App\Listeners;

use App\Events\LessonProgressUpdated;
use App\Events\ScoreCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UpdateLessonProgress implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ScoreCreated $event): void
    {
        $score = $event->score;

        $user = $score->user;
        $scorable = $score->scorable;

        $lesson = $score->scorable->lesson;
        if (! $lesson) {
            return;
        }

        $pivot = DB::table('lesson_user')
            ->where('lesson_id', $lesson->id)
            ->where('user_id', $user->id)
            ->first();

        if (! $pivot) {
            return;
        }

        $scoreCount = $scorable
            ->scores()
            ->where('user_id', $user->id)
            ->where('score', 1)
            ->count();

        if ($scorable instanceof \App\Models\Deck) {
            if ($pivot->stage < 2 && $scoreCount >= 3) {
                $user->lessons()->updateExistingPivot($lesson->id, [
                    'stage' => 2,
                ]);

                LessonProgressUpdated::dispatch(
                    "Great job! You've unlocked the Activity for this Lesson!",
                    'success',
                    $user->id
                );
            }

        } else if ($scorable instanceof \App\Models\Activity) {
            if ($pivot->stage === 2 && $scoreCount >= 1) {
                $user->lessons()->updateExistingPivot($lesson->id, [
                    'stage' => 3,
                    'completed' => true,
                ]);

                $newlyUnlocked = \App\Services\LessonService::syncUserProgress($user);

                if ($newlyUnlocked->isNotEmpty()) {
                    $count = $newlyUnlocked->count();
                    $firstSlug = $newlyUnlocked->first()->global_position;

                    $message = $count === 1
                        ? "Lesson completed! You've unlocked Lesson $firstSlug."
                        : "Lesson completed! You've unlocked $count new Lessons.";

                    \App\Events\LessonProgressUpdated::dispatch($message, 'success', $user->id);

                } else {
                    \App\Events\LessonProgressUpdated::dispatch(
                        "You've finished the Lesson & completed all the content you're eligible for in the Academy.",
                        'success',
                        $user->id
                    );
                }
            }
        }
    }
}
