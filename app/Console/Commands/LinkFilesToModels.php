<?php

namespace App\Console\Commands;

use App\Exceptions\AudioFileException;
use App\Services\AudioService;
use Illuminate\Console\Command;

class LinkFilesToModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates file database entries for sentences, inflections, and pronunciations';

    /**
     * Execute the console command.
     *
     * @throws AudioFileException
     */
    public function handle(AudioService $audioService)
    {
        $filesAdded = $audioService->addAudioFilesDatabase();
        $this->info('All file database entries have been created');
        $this->info('Files linked to models:');
        foreach ($filesAdded as $file) {
            $this->info($file);
        }
    }
}
