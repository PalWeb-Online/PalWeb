<?php

namespace App\Exceptions;

use Exception;

class AudioFileException extends Exception
{
    /**
     * Thrown if the file provided did not have a file extension (example audio.mp3)
     */
    public static function noFileExtension(string $name)
    {
        $msg = $name.' does not have a file extension';

        return new static($msg);
    }
}
