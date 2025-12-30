<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ForceSyncLessonProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lessons:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unlock Lessons for eligible Students & Admins.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Unlocking first Lesson for all Student users...');

        $userIds = DB::table('model_has_roles')->pluck('model_id');
        $this->info("Found {$userIds->count()} Student(s).");

        $totalUnlocked = 0;

        foreach ($userIds as $userId) {
            $user = User::find($userId);

            if ($user) {
                $totalUnlocked = \App\Services\LessonService::syncUserProgress($user)->count();
            }
        }

        $this->info("Successfully synced progress. {$totalUnlocked} Lessons unlocked for {$userIds->count()} user(s).");
    }
}
