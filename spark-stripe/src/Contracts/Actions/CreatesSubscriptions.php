<?php

namespace Spark\Contracts\Actions;

interface CreatesSubscriptions
{
    /**
     * Generate a checkout session for subscription.
     *
     * @param  \Spark\Billable  $billable
     * @param  string  $plan
     * @return \Laravel\Cashier\Checkout
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Laravel\Cashier\Exceptions\IncompletePayment
     */
    public function create($billable, $plan, array $options = []);
}
