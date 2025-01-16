<?php

namespace Spark;

use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Mpociot\VatCalculator\VatCalculator;

class ValidVatNumber implements ValidationRule
{
    /**
     * Validate the attribute value.
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        try {
            if (! app(VatCalculator::class)->isValidVATNumber($value)) {
                $fail(__('The provided VAT number is invalid.'));
            }

        } catch (Exception $e) {
            $fail(__('The provided VAT number is invalid.'));
        }
    }
}
