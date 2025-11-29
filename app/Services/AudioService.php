<?php

namespace App\Services;

use App\Exceptions\AudioFileException;
use App\Models\Inflection;
use App\Models\Pronunciation;
use App\Models\Sentence;
use App\Repositories\Audio\AudioDirectoryRepository;
use App\Repositories\Audio\UploadAudioFileRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AudioService
{
    public function __construct(
        protected AudioDirectoryRepository $audioDirectory,
        protected UploadAudioFileRepository $audioUploader,
    ) {
    }

    public function uploadAudio(string $wavPath, string $filename): void
    {
        $mp3Path = str_replace('.wav', '.mp3', $wavPath);

        try {
            $this->convertToMp3($wavPath, $mp3Path);

            if (! app()->environment(['local', 'testing'])) {
                Storage::disk('s3')->putFileAs('audios', new \Illuminate\Http\File($mp3Path), $filename, 'public');

            } else {
                Log::info('Simulating audio file upload to s3: '.$filename);
            }

        } catch (\Exception $e) {
            Log::error('Failed to upload audio file: '.$e->getMessage());
            throw $e;

        } finally {
            File::delete($mp3Path);
        }
    }

    protected function convertToMp3(string $wavPath, string $mp3Path): void
    {
        if (! file_exists($wavPath)) {
            throw new \Exception("Source file not found: {$wavPath}");
        }

        $command = 'ffmpeg -loglevel panic -i '.escapeshellarg($wavPath).' -b:a 128k '.escapeshellarg($mp3Path);
        $output = [];
        $returnVar = null;

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("Failed to convert audio file to .mp3. Command: {$command}, Output: ".implode("\n",
                    $output));
        }
    }

    public function renameAudio(string $currentFilename, string $newFilename): void
    {
        $currentPath = 'audios/'.$currentFilename;
        $newPath = 'audios/'.$newFilename;

        try {
            if (! Storage::disk('s3')->exists($currentPath)) {
                throw new \Exception("File not found: {$currentPath}");
            }

            if (! app()->environment(['local', 'testing'])) {
                Storage::disk('s3')->copy($currentPath, $newPath);
                Storage::disk('s3')->delete($currentPath);

            } else {
                Log::info('Simulating audio file rename from '.$currentFilename.' to '.$newFilename);
            }

        } catch (\Exception $e) {
            throw new \Exception("Failed to rename file from {$currentFilename} to {$newFilename}: ".$e->getMessage(),
                0, $e);
        }
    }

    public function deleteAudio(string $filename): void
    {
        $filePath = 'audios/'.$filename;

        try {
            if (! app()->environment(['local', 'testing'])) {
                if (! Storage::disk('s3')->exists($filePath)) {
                    throw new \Exception("File not found: {$filePath}");
                }

                Storage::disk('s3')->delete($filePath);

            } else {
                Log::info('Simulating audio file deletion from s3: '.$filename);
            }

        } catch (\Exception $e) {
            throw new \Exception("Failed to delete file: {$filePath}. Error: ".$e->getMessage(), 0, $e);
        }
    }

    /**
     * Return an array of all models which have audio files related to them.
     *
     * @return array the array of fileable models
     */
    public function getFileables(): array
    {
        $sentences = Sentence::all();
        $inflections = Inflection::all();
        $pronunciations = Pronunciation::all();

        return [$sentences, $inflections, $pronunciations];
    }

    /**
     * Creates file model database entries for all fileable models.
     *
     * @return array the files added to the database and linked to models
     *
     * @throws AudioFileException
     */
    public function addAudioFilesDatabase(): array
    {
        $filesAddedToDatabase = [];
        $fileables = $this->getFileables();

        foreach ($fileables as $fileable) {
            foreach ($fileable as $model) {
                // fileable models go by audified translit
                $mp3Name = $model->audify().'.mp3';

                $existsInDatabase = $this->audioDirectory->fileExistsInDatabase($mp3Name);

                if (! $existsInDatabase) {
                    $this->audioDirectory->addAudioFile($model, $mp3Name);
                    $filesAddedToDatabase[] = $mp3Name;
                }
            }
        }

        return $filesAddedToDatabase;
    }

    /**
     * Uploads all given audio files to s3 bucket.
     *
     * @param  array  $files  the array of files to be uploaded
     * @param  bool  $forced  forced mode will replace old files if a new file of the same name is found
     */
    public function uploadAudioFilesS3(array $files, bool $forced, Command $command): void
    {
        foreach ($files as $file) {
            // skip hidden files
            if (str_starts_with($file, 'audio/.')) {
                continue;
            }

            $this->uploadAudioFileS3($file, $forced, $command);
        }
    }

    /**
     * Uploads a given file to the s3 bucket.
     *
     * @param  string  $file  the path to the file to upload
     * @param  bool  $forced  if true replaces file if duplicate is found
     */
    public function uploadAudioFileS3(string $file, bool $forced, Command $command): void
    {
        // split the path up and get the last item in the path to be the file for upload
        $path = explode('/', $file);
        $originalName = array_pop($path);
        $originalName = explode('.', $originalName);

        // get the raw file name without its extension and create a mp3 format name
        $fileTranslit = $originalName[0];
        $mp3Name = $fileTranslit.'.mp3';

        $originalName = implode('.', $originalName);

        // upload the file to the s3 database if it's not already been uploaded
        $isUploaded = $this->audioUploader->audioFileIsUploaded($mp3Name);

        if (! $isUploaded || $forced) {
            $this->audioUploader->uploadAudioFile($originalName, $mp3Name);
            $command->info("NEW $fileTranslit");
        } else {
            // Note: This tightly couples this service class to the command-line context.
            // Consider refactoring if this class needs to be used in other contexts.
            $command->info("IGNORED $fileTranslit");
        }
    }
}
