<?php

namespace App\Console\Commands;

use App\Models\Audio;
use App\Models\Pronunciation;
use App\Models\Speaker;
use App\Services\AudioService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProcessAudio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:audio {speaker_name} {speaker_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process audio files and create corresponding Audio models with new filenames.';

    public function __construct(protected AudioService $audioService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! $this->confirm('You must only run this command on the production server. Are you sure you would like to continue?')) {
            $this->warn('Aborted.');

            return 1;
        }

        $speakerName = $this->argument('speaker_name');
        $audioFolder = 'audios_backup/'.$speakerName;

        if (! Storage::disk('s3')->exists($audioFolder)) {
            $this->error('Directory with audio files does not exist.');

            return 1;
        }

        $files = Storage::disk('s3')->files($audioFolder);

        if (empty($files)) {
            $this->info('No audio files found in the directory.');

            return 0;
        }

        $this->info('Processing audio files...');

        $speakerId = $this->argument('speaker_id');
        $speaker = Speaker::findOrFail($speakerId);

        $dialect = $speaker->dialect;
        $dialectIds = $dialect->ancestors->sortDesc()->pluck('id')->prepend($dialect->id);

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) !== 'wav') {
                $this->warn("Skipping non-audio file: {$file}");

                continue;
            }

            $this->info("Processing file: {$file}");

            $translit = pathinfo($file, PATHINFO_FILENAME);
            $pronunciation = Pronunciation::whereIn('dialect_id', $dialectIds)->where('translit', $translit)->get();

            if ($pronunciation->count() > 1) {
                $this->warn("More than one Pronunciation found for: {$translit}. Skipping...");

            } elseif ($pronunciation->count() === 0) {
                $this->warn("No Pronunciation found for: {$translit}. Skipping...");

            } else {
                $pronunciation = $pronunciation->first();
                $wavFilename = pathinfo($file, PATHINFO_BASENAME);
                $mp3Filename = 'apc-'.$speaker->dialect_id.'-'.$speaker->location_id.'-'.$pronunciation->id.'-'.$pronunciation->translit.'-'.$speaker->id.'.mp3';

                Audio::create([
                    'filename' => $mp3Filename,
                    'speaker_id' => $speaker->id,
                    'pronunciation_id' => $pronunciation->id,
                ]);

                $fileContent = Storage::disk('s3')->get($file);
                $tempWavPath = storage_path('app/temp/'.$wavFilename);

                if (! File::exists(storage_path('app/temp'))) {
                    File::makeDirectory(storage_path('app/temp'), 0755, true);
                }

                File::put($tempWavPath, $fileContent);

                $this->audioService->uploadAudio($tempWavPath, $mp3Filename);

                File::delete($tempWavPath);
                Storage::disk('s3')->delete($file);

                $this->info("[COMPLETE] {$mp3Filename}: Audio created, file uploaded to S3 bucket & deleted from local storage.");
            }
        }

        $this->info('Finished processing files.');
    }
}
