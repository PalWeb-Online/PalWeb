<?php

namespace Spark\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Spark\Contracts\Actions\UpdatesSubscriptions;
use Spark\Spark;
use Spark\ValidPlan;

class UpdateSubscriptionController
{
    use RetrievesBillableModels;

    /**
     * Update the plan that the billable is currently subscribed to.
     *
     * @return void
     */
    public function __invoke(Request $request)
    {
        $billable = $this->billable();

        $subscription = $billable->subscription();

        if (! $subscription) {
            throw ValidationException::withMessages([
                '*' => __('This account does not have an active subscription.'),
            ]);
        }

        if (is_null($billable->defaultPaymentMethod())) {
            throw ValidationException::withMessages([
                '*' => __('Please set a default payment method before swapping plans.'),
            ]);
        }

        $request->validate([
            'plan' => ['required', new ValidPlan($request->billableType)],
        ]);

        Spark::ensurePlanEligibility(
            $billable,
            Spark::plans($billable->sparkConfiguration('type'))
                ->where('id', $request->plan)
                ->first()
        );

        try {
            app(UpdatesSubscriptions::class)->update($subscription, $request->plan);

            session(['spark.flash.success' => __('Your subscription was successfully updated.')]);
        } catch (IncompletePayment $e) {
            return response()->json([
                'paymentId' => $e->payment->id,
            ]);
        }
    }
}
