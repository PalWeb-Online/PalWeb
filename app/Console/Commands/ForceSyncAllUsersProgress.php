<?php

namespace App\Console\Commands;

use App\Jobs\SyncAllUsersProgress;
use Illuminate\Console\Command;

class ForceSyncAllUsersProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lessons:sync {--now : Run the sync immediately instead of queueing it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unlock Lessons for eligible Students & Admins via background job.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('now')) {
            $this->info('Starting lesson sync immediately...');
            SyncAllUsersProgress::dispatchSync();
            $this->info('Sync completed.');
            return;
        }

        $this->info('Dispatching lesson sync job to the queue...');
        SyncAllUsersProgress::dispatch();
        $this->info('Job dispatched. You can monitor progress in the logs or failed_jobs table.');
    }
}
