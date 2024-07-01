<?php

namespace Spark\Actions;

use Mpociot\VatCalculator\Exceptions\VATCheckUnavailableException;
use Mpociot\VatCalculator\VatCalculator;
use Spark\Contracts\Actions\CalculatesVatRate;

class CalculateVatRate implements CalculatesVatRate
{
    /**
     * {@inheritDoc}
     */
    public function calculate($homeCountry, $country, $postalCode, $vatNumber)
    {
        $vatCalculator = app(VatCalculator::class);

        $vatCalculator->setBusinessCountryCode($homeCountry);

        try {
            $isValidVAT = ! empty($vatNumber) && $vatCalculator->isValidVATNumber($vatNumber);
        } catch (VATCheckUnavailableException $e) {
            $isValidVAT = false;
        }

        return $vatCalculator->getTaxRateForLocation(
            $country,
            $postalCode,
            $isValidVAT
        ) * 100;
    }
}
