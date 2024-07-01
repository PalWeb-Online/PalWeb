<?php

namespace Spark\Contracts\Actions;

interface CalculatesVatRate
{
    /**
     * Calculate the VAT rate.
     *
     * @param  string  $homeCountry
     * @param  string  $country
     * @param  string  $postalCode
     * @param  string  $vatNumber
     * @return float
     */
    public function calculate($homeCountry, $country, $postalCode, $vatNumber);
}
