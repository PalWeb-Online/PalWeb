<?php

namespace App\Repositories\Audio;

use App\Exceptions\AudioFileException;
use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class AudioDirectoryRepository
{
    /**
     * Determines if a given filename already exist in the files table.
     *
     * @param string $name the file name to search for
     * @return bool true if the file name already has an entry in the files table, false otherwise
     */
    public function fileExistsInDatabase(string $name): bool
    {
        return File::query()
            ->where('filename', '=', $name)
            ->exists();
    }


    /**
     * Add a given audio file to the database and relate it to its correct model.
     *
     * @param Model $model the fileable model
     * @param string $name the filename
     * @param string $path the path to the file in the s3 bucket
     * @return void
     * @throws AudioFileException
     */
    public function addAudioFile(Model $model, string $name, string $path = 'audio'): void
    {
        // check to make sure given filename is a valid filename
        if (count(explode('.', $name)) < 2) {
            throw AudioFileException::noFileExtension($name);
        }

        File::create([
            'uuid' => Uuid::uuid4()->toString(),
            'filename' => $name,
            'path' => $path,
            'extension' => 'mp3',
            'fileable_type' => $model->getMorphClass(),
            'fileable_id' => $model->id,
        ]);
    }
}
