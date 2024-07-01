<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ArabicScript implements Rule
{
    public function __construct()
    {
    }

    public function passes($attribute, $value)
    {
        return preg_match('/^[\p{Arabic}\s]+$/u', $value);
    }

    public function message()
    {
        return 'This field may only contain Arabic-script characters.';
    }
}
