<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\Pronunciation;
use Illuminate\Console\Command;

class ProcessAudio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:audio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process audio files and create corresponding Audio models with new filenames.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $audioPath = storage_path('app/audio');

        if (!is_dir($audioPath)) {
            $this->error('Audio directory does not exist.');
            return;
        }

        $files = array_diff(scandir($audioPath), ['.', '..']);
        if (empty($files)) {
            $this->info('No audio files found in the directory.');
            return;
        }

        $this->info('Processing audio files...');

        $language = 'apc';
        $speakerId = 1;

        $location = Location::where('name', 'رام الله')->first();
        if (!$location) {
            $this->error("Location not found.");
            return;
        }

        $foundPronunciations = [];
        $missingPronunciations = [];

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) !== 'wav') {
                $this->warn("Skipping non-audio file: {$file}");
                continue;
            }

            $this->info("Processing file: {$file}");

            $filename = pathinfo($file, PATHINFO_FILENAME);

            $pronunciation = Pronunciation::where('translit', $filename)->first();
            if ($pronunciation) {
                $dialect = $pronunciation->dialect;

                if (!$dialect) {
                    $this->warn("Dialect not found.");
                    continue;
                }

                $newFilename = $language.'-'.
                    $dialect->name.'-'.
                    $location->name_en.'-'.
                    $pronunciation->translit.'-'.
                    $speakerId.'.wav';

                $foundPronunciations[] = $newFilename;

            } else {
                $this->warn("No Pronunciation found for: {$filename}");

                $missingPronunciations[] = $file;
            }

//            TODO: list found Pronunciations in one file; not found ones in another file.
//             found ones should be processed normally; the rest to be processed manually.

//            $oldFilePath = $audioPath . DIRECTORY_SEPARATOR . $file;
//            $newFilePath = $audioPath . DIRECTORY_SEPARATOR . $newFilename;
//
//            if (!rename($oldFilePath, $newFilePath)) {
//                $this->error("Failed to rename file: {$file}");
//                continue;
//            }

//            Audio::create([
//                'filename' => $newFilename,
//                'speaker_id' => $speakerId,
//                'pronunciation_id' => $pronunciation->id,
//            ]);

//            $this->info("File processed and Audio model created for: {$newFilename}");
        }

        $this->info('All files have been processed.');
        $this->info('Found Pronunciations: ' . print_r($foundPronunciations, true));
        $this->info('Missing Pronunciations: ' . print_r($missingPronunciations, true));
    }
}
