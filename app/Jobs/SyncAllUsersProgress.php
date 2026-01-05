<?php

namespace App\Jobs;

use App\Models\Lesson;
use App\Models\User;
use App\Services\LessonService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncAllUsersProgress implements ShouldQueue
{
    use Queueable;

    public $timeout = 600;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        User::role(['student', 'admin'])
            ->with('lessons')
            ->chunk(100, function ($users) {
                foreach ($users as $user) {
                    LessonService::syncUserProgress($user);
                }
            });
    }
}
