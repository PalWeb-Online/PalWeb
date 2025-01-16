<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class ArabicScript implements ValidationRule
{
    /**
     * Validate the attribute value.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  \Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (! preg_match('/^[\p{Arabic}\s]+$/u', $value)) {
            $fail('This field may only contain Arabic-script characters.');
        }
    }
}
