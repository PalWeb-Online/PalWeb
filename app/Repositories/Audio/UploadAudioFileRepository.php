<?php

namespace App\Repositories\Audio;

use Illuminate\Support\Facades\Storage;

class UploadAudioFileRepository
{
    /**
     * Determines if a given audio file is already in the s3 bucket already or not.
     *
     * @param string $mp3_name the filename to search for in the s3 bucket
     * @return bool
     */
    public function audioFileIsUploaded(string $mp3_name): bool
    {
        return Storage::disk('s3')->exists('audio/' . $mp3_name);
    }

    /**
     * Upload given audio file to the s3 bucket.
     *
     * @param string $original_name the original filename
     * @param string $mp3_name the mp3 formatted name, used for conversion
     * @return string the resultant storage upload entry
     */
    public function uploadAudioFile(string $original_name, string $mp3_name): string
    {
        // .mp3 is much smaller than .wav, therefore we convert first
        $this->convertFile($original_name, $mp3_name);

        $result = Storage::disk('s3')->putFileAs(
            'audio/',
            storage_path('app/converted_audio/') . $mp3_name,
            $mp3_name,
            'public'
        );

        // remove file from local converted directory
        $this->removeTempFile($mp3_name);

        return $result;
    }

    /**
     * Converts a given audio file to mp3 format.
     *
     * @param string $original_name the original name of the file to be converted
     * @param string $mp3_name the mp3 formatted name to save the converted file as
     * @return void
     */
    protected function convertFile(string $original_name, string $mp3_name): void
    {
        $exitCode = shell_exec('ffmpeg -loglevel quiet -i ' . storage_path('app/audio/') . escapeshellcmd($original_name) . ' ' . storage_path('app/converted_audio/') . escapeshellcmd($mp3_name) . '; echo $?');

        if ($exitCode != 0) {
            $this->info("Conversion for $original_name failed with exit code $exitCode");
        }
    }


    /**
     * Removes mp3 formatted file from temporary converted audio directory.
     *
     * @param string $mp3_name the mp3 formatted name to remove from the temporary directory
     * @return void
     */
    protected function removeTempFile(string $mp3_name): void
    {
        shell_exec('rm ' . storage_path('app/converted_audio/' . escapeshellcmd($mp3_name)));
    }
}
