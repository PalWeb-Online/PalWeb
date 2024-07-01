<?php

namespace App\Exceptions;

use Exception;

class NullBookmarkException extends Exception
{
    public static function forBookmark($bookmark)
    {
        $className = get_class($bookmark);

        return new static('variable $bookmarkModel in '.$className." can't be null");
    }
}
