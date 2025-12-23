<?php

namespace App\Listeners;

use App\Events\LessonProgressUpdated;
use App\Events\ScoreCreated;
use App\Models\Lesson;
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

                $nextLesson = Lesson::where('slug', '>', $lesson->slug)
                    ->orderBy('slug')
                    ->first();

                if ($nextLesson && ! $user->lessons()->where('lesson_id', $nextLesson->id)->exists()) {
                    $user->lessons()->attach($nextLesson->id);

                    LessonProgressUpdated::dispatch(
                        "You've finished the Lesson & unlocked the Dialog! You can proceed to Lesson $nextLesson->slug!",
                        'success',
                        $user->id
                    );
                }
            }
        }
    }
}
