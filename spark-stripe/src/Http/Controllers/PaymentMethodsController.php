<?php

namespace Spark\Http\Controllers;

use Illuminate\Http\Request;
use Spark\Features;

class PaymentMethodsController
{
    use RetrievesBillableModels;

    /**
     * Setup a billing method for the billable entity.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setup()
    {
        $checkout = $this->billable()->checkout([], array_filter([
            'mode' => 'setup',
            'payment_method_types' => ['card'],
            'billing_address_collection' => Features::collectsBillingAddress() ? 'required' : null,
            'success_url' => route('spark.portal').'?checkout=payment_method_added',
            'cancel_url' => route('spark.portal').'?checkout=cancelled',
        ]));

        return response()->json([
            'redirect' => $checkout->url,
        ]);
    }

    /**
     * Set the default billing method for the billable entity.
     *
     * @return void
     */
    public function default(Request $request)
    {
        $request->validate([
            'payment_method' => ['required', 'string'],
        ]);

        $this->billable()->updateDefaultPaymentMethod($request->payment_method);
    }

    /**
     * Delete a billing method of the billable entity.
     *
     * @return void
     */
    public function delete(Request $request)
    {
        $request->validate([
            'payment_method' => ['required', 'string'],
        ]);

        $this->billable()->deletePaymentMethod($request->payment_method);
    }
}
