<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UnlockFirstLessonForStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lessons:unlock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unlock the first Lesson for subscribed users & admins.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Unlocking first Lesson for all Student users...');

        $firstLesson = \App\Models\Lesson::orderBy('slug')->first();

        if (!$firstLesson) {
            $this->error('No Lessons found in the database.');
            return;
        }

        $studentIds = DB::table('model_has_roles')
            ->pluck('model_id');

        $this->info("Found {$studentIds->count()} Student(s).");

        $created = 0;

        foreach ($studentIds as $userId) {
            $user = User::find($userId);

            if (!$user) {
                continue;
            }

            $alreadyUnlocked = DB::table('lesson_user')
                ->where('lesson_id', $firstLesson->id)
                ->where('user_id', $userId)
                ->exists();

            if (!$alreadyUnlocked) {
                $user->lessons()->attach($firstLesson->id);
                $created++;
            }
        }

        $this->info("Unlocked Lesson $firstLesson->slug for {$created} students.");
    }
}
