<?php

namespace Spark\Http\Controllers;

use Illuminate\Validation\ValidationException;

class CancelSubscriptionController
{
    use RetrievesBillableModels;

    /**
     * Cancel the billable's current subscription.
     *
     * @return void
     */
    public function __invoke()
    {
        $billable = $this->billable();

        $subscription = $billable->subscription();

        if (! $subscription) {
            throw ValidationException::withMessages([
                '*' => __('This account does not have an active subscription.'),
            ]);
        }

        $subscription->cancel();

        session(['spark.flash.success' => __('Your subscription has been successfully cancelled.')]);
    }
}
