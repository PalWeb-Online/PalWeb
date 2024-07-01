<?php

namespace App\Services;

use App\Exceptions\AudioFileException;
use App\Models\Inflection;
use App\Models\Pronunciation;
use App\Models\Sentence;
use App\Repositories\Audio\AudioDirectoryRepository;
use App\Repositories\Audio\UploadAudioFileRepository;
use Illuminate\Console\Command;

class AudioService
{
    public function __construct(
        protected AudioDirectoryRepository  $audioDirectory,
        protected UploadAudioFileRepository $audioUploader,
    )
    {
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
     * @throws AudioFileException
     */
    public function addAudioFilesDatabase(): array
    {
        $filesAddedToDatabase = [];
        $fileables = $this->getFileables();

        foreach ($fileables as $fileable) {
            foreach ($fileable as $model) {
                // fileable models go by audified translit
                $mp3Name = $model->audify() . '.mp3';

                $existsInDatabase = $this->audioDirectory->fileExistsInDatabase($mp3Name);

                if (!$existsInDatabase) {
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
     * @param array $files the array of files to be uploaded
     * @param bool $forced forced mode will replace old files if a new file of the same name is found
     * @return void
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
     * @param string $file the path to the file to upload
     * @param bool $forced if true replaces file if duplicate is found
     * @return void
     */
    public function uploadAudioFileS3(string $file, bool $forced, Command $command): void
    {
        // split the path up and get the last item in the path to be the file for upload
        $path = explode('/', $file);
        $originalName = array_pop($path);
        $originalName = explode('.', $originalName);

        // get the raw file name without its extension and create a mp3 format name
        $fileTranslit = $originalName[0];
        $mp3Name = $fileTranslit . '.mp3';

        $originalName = implode('.', $originalName);

        // upload the file to the s3 database if it's not already been uploaded
        $isUploaded = $this->audioUploader->audioFileIsUploaded($mp3Name);

        if (!$isUploaded || $forced) {
            $this->audioUploader->uploadAudioFile($originalName, $mp3Name);
            $command->info("NEW $fileTranslit");
        } else {
            // Note: This tightly couples this service class to the command-line context.
            // Consider refactoring if this class needs to be used in other contexts.
            $command->info("IGNORED $fileTranslit");
        }
    }
}
