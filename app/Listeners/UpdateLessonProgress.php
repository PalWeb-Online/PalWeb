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

//        if DeckScoreCreated & ActivityScoreCreated are managed separately, the conditional is unnecessary
        if ($scorable instanceof \App\Models\Deck) {
            $deck = $scorable;
            $lesson = Lesson::where('deck_id', $scorable->id)->first();
        }

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

        $deckScoreCount = $deck
            ->scores()
            ->where('user_id', $user->id)
            ->where('score', 1)
            ->count();

        if ($pivot->stage < 2 && $deckScoreCount >= 3) {
            $user->lessons()->updateExistingPivot($lesson->id, [
                'stage' => 2,
            ]);

            LessonProgressUpdated::dispatch(
                "Great job! You've unlocked the Skills for this Lesson!",
                'success',
                $user->id
            );
        }

        // (Future) If Activity complete, stage 2 → 3
        // For now, we can simulate completion when stage = 2

        if ($pivot->stage < 3 && $pivot->stage === 2) {
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
                    "Amazing! You've unlocked Lesson $nextLesson->slug!",
                    'success',
                    $user->id
                );
            }

        }
    }
}
