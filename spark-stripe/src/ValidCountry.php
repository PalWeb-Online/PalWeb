<?php

namespace Spark;

use Illuminate\Contracts\Validation\Rule;

class ValidCountry implements Rule
{
    /**
     * {@inheritDoc}
     */
    public function passes($attribute, $value)
    {
        return in_array(
            $value,
            array_keys(Countries::all())
        );
    }

    /**
     * {@inheritDoc}
     */
    public function message()
    {
        return __('The selected country is invalid.');
    }
}
