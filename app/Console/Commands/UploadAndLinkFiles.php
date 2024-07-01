<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UploadAndLinkFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:migrate {--force : forces file uploads to s3 bucket, will replace existing file in s3 bucket if file of the same name appears}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calls file:upload and file:link';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $forced = $this->option('force');
        if ($forced){
            $this->call('file:upload --force');
        } else {
            $this->call('file:upload');
        }
        $this->call('file:link');
        $this->info("Files uploaded and database entries created");
    }
}
