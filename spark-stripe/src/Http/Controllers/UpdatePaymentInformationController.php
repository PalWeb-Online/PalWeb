<?php

namespace Spark\Http\Controllers;

use Illuminate\Http\Request;
use Spark\Features;
use Spark\ValidCountry;
use Spark\ValidVatNumber;

class UpdatePaymentInformationController
{
    use RetrievesBillableModels;

    /**
     * Update the billing information for the billable entity.
     *
     * @return void
     */
    public function __invoke(Request $request)
    {
        $billable = $this->billable();

        $this->validate($request);

        $this->updateBillable($request, $billable);

        $billable->subscription()->syncTaxRates();

        session(['spark.flash.success' => __('Payment information updated successfully.')]);
    }

    /**
     * Update the billable from the request.
     *
     * @param  \Spark\Billable  $billable
     * @return void
     */
    private function updateBillable(Request $request, $billable)
    {
        $billable->forceFill($request->only([
            'extra_billing_information',
            'billing_address',
            'billing_address_line_2',
            'billing_city',
            'billing_state',
            'billing_postal_code',
            'billing_country',
            'vat_id',
        ]))->save();
    }

    /**
     * Validate the incoming request.
     *
     * @return void
     */
    protected function validate(Request $request)
    {
        $countryRule = Features::collectsBillingAddress() ? 'required' : 'nullable';

        $addressRequired = Features::collectsBillingAddress() &&
            (bool) Features::option('billing-address-collection', 'required');

        $request->validate([
            'billing_address' => [$addressRequired ? 'required' : 'nullable', 'max:225'],
            'billing_address_line_2' => ['nullable', 'max:225'],
            'billing_city' => [$addressRequired ? 'required' : 'nullable', 'max:225'],
            'billing_state' => [$addressRequired ? 'required' : 'nullable', 'max:225'],
            'billing_postal_code' => [$addressRequired ? 'required' : 'nullable', 'max:225'],
            'billing_country' => [$countryRule, 'max:2', new ValidCountry()],
            'vat_id' => ['nullable', 'max:225', new ValidVatNumber()],
        ]);
    }
}
