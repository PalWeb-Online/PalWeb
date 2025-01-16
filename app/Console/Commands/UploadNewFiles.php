<?php

namespace App\Console\Commands;

use App\Services\AudioService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class UploadNewFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:upload {--force : forces file uploads to s3 bucket, will replace existing file in s3 bucket if file of the same name appears}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uploads any new files placed in /storage/app/audio that are not found in the s3 bucket.';

    /**
     * Execute the console command.
     */
    public function handle(AudioService $audioService)
    {
        $forced = $this->option('force');

        $files = Storage::disk('local')->allFiles('audio');
        $audioService->uploadAudioFilesS3($files, $forced, $this);
        $this->info('All files have been uploaded to the S3 bucket');
    }
}
