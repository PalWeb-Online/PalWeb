<?php

namespace App\Exceptions;

use Exception;

/**
 * Called when we are denied access to a controller method through a laravel policy class
 */
class UnauthorizedAccessException extends Exception
{
    public static function forClass($class)
    {
        // Returns the controller function that we were denied access to. Technically we don't need the $class arg
        // but it helps explain wtf is going on here.
        $function = debug_backtrace()[2]['function'];

        $text = "You do not have permission to access {$function} method of Controller {$class}";

        return new static($text);
    }
}
