<?php

namespace App\Listeners;

use App\Events\ScoreCreated;
use App\Models\Lesson;
use App\Models\User;
use App\Services\LessonService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class SyncLessonProgress implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct(public Lesson $lesson)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ScoreCreated $event): void
    {
        User::role(['student', 'admin'])->chunk(100, function ($users) {
            foreach ($users as $user) {
                LessonService::syncUserProgress($user);
            }
        });
    }
}
