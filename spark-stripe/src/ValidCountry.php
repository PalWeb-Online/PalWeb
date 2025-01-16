<?php

namespace Spark;

use Illuminate\Contracts\Validation\ValidationRule;

class ValidCountry implements ValidationRule
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
        if (!in_array($value, array_keys(Countries::all()))) {
            $fail(__('The selected country is invalid.'));
        }
    }
}
