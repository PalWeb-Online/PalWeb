<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class LatinScript implements ValidationRule
{
    /**
     * Validate the attribute value.
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (! preg_match('/^[\p{Latin}\s\-]+$/u', $value)) {
            $fail('This field may only contain Latin-script letters & hyphens.');
        }
    }
}
