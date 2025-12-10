<?php

namespace Spark\Http\Controllers;

use Illuminate\Http\Request;
use Spark\Contracts\Actions\CreatesSubscriptions;
use Spark\Features;
use Spark\Spark;
use Spark\ValidCountry;
use Spark\ValidPlan;
use Spark\ValidVatNumber;

class NewSubscriptionController
{
    use RetrievesBillableModels;

    /**
     * Create a new subscription.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $billable = $this->billable();

        $this->validate($request);

        Spark::ensurePlanEligibility(
            $billable,
            Spark::plans($billable->sparkConfiguration('type'))
                ->where('id', $request->plan)
                ->first()
        );

        $this->updateBillable($request, $billable);

        $checkout = app(CreatesSubscriptions::class)->create(
            $billable,
            $request->plan,
            ['coupon' => $request->coupon]
        );

        return response()->json([
            'redirect' => $checkout->url,
        ]);
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
     */
    protected function validate(Request $request)
    {
        $countryRule = Features::collectsBillingAddress() ? 'required' : 'nullable';

        $addressRequired = Features::collectsBillingAddress() &&
            (bool) Features::option('billing-address-collection', 'required');

        $request->validate([
            'plan' => ['required', new ValidPlan($request->billableType)],
            'extra_billing_information' => 'max:2048',
            'billing_address' => [$addressRequired ? 'required' : 'nullable', 'max:225'],
            'billing_address_line_2' => ['nullable', 'max:225'],
            'billing_city' => [$addressRequired ? 'required' : 'nullable', 'max:225'],
            'billing_state' => [$addressRequired ? 'required' : 'nullable', 'max:225'],
            'billing_postal_code' => [$addressRequired ? 'required' : 'nullable', 'max:225'],
            'billing_country' => [$countryRule, 'max:2', new ValidCountry],
            'vat_id' => ['nullable', 'max:225', new ValidVatNumber],
        ]);
    }
}
