<?php

namespace Spark\Contracts\Actions;

interface UpdatesSubscriptions
{
    /**
     * Update the billable subscription to the given plan.
     *
     * @param  \Laravel\Cashier\Subscription  $subscription
     * @param  string  $plan
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($subscription, $plan);
}
