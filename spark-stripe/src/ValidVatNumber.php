<?php

namespace Spark;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Mpociot\VatCalculator\VatCalculator;

class ValidVatNumber implements Rule
{
    /**
     * {@inheritDoc}
     */
    public function passes($attribute, $value)
    {
        try {
            return app(VatCalculator::class)->isValidVATNumber($value);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function message()
    {
        return __('The provided VAT number is invalid.');
    }
}
