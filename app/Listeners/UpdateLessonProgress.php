<?php

namespace App\Listeners;

use App\Events\AcademyProgressChanged;
use App\Events\ScoreCreated;
use App\Events\UserNotificationSent;
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
        if (! $scorable) {
            \Log::error("Scorable not found for Score {$score->id}. If this is an Activity, make sure the Lesson is published.");
            return;
        }

        $lesson = $scorable->lesson;
        if (! $lesson) {
            \Log::error("Lesson not found for Scorable {$scorable->id}. Are you sure both the Unit & the Lesson are published?");
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
            ->where('score', '>=', 1)
            ->count();

        if ($scorable instanceof \App\Models\Deck && $pivot->stage === 1 && $scoreCount >= 3) {
            $user->lessons()->updateExistingPivot($lesson->id, [
                'stage' => 2,
            ]);

            UserNotificationSent::dispatch($user->id, [
                'key' => 'lesson.notifications.activity-unlocked',
                'type' => 'success',
            ]);

        } else if ($scorable instanceof \App\Models\Activity && $pivot->stage === 2 && $scoreCount >= 1) {
            $user->lessons()->updateExistingPivot($lesson->id, [
                'stage' => 3,
                'completed' => true,
            ]);

            $newlyUnlocked = \App\Services\LessonService::syncUserProgress($user);

            if ($newlyUnlocked->isNotEmpty()) {
                $count = $newlyUnlocked->count();
                $position = $newlyUnlocked->first()->global_position;

                if ($count === 1) {
                    UserNotificationSent::dispatch($user->id, [
                        'key' => 'lesson.notifications.unlocked-lesson',
                        'params' => [
                            'position' => $position,
                        ]
                    ]);

                } else {
                    UserNotificationSent::dispatch($user->id, [
                        'key' => 'lesson.notifications.unlocked-lessons',
                        'params' => [
                            'count' => $count,
                        ]
                    ]);
                }

            } else {
                UserNotificationSent::dispatch($user->id, [
                    'key' => 'lesson.notifications.completed-everything',
                ]);
            }
        }

        AcademyProgressChanged::dispatch($user->id);
    }
}
