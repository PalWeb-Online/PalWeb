<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class LatinScript implements Rule
{
    public function __construct()
    {
    }

    public function passes($attribute, $value)
    {
        return preg_match('/^[\p{Latin}\s\-]+$/u', $value);
    }

    public function message()
    {
        return 'This field may only contain Latin-script letters & hyphens.';
    }
}
