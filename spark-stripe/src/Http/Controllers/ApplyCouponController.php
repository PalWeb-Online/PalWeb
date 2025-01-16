<?php

namespace Spark\Http\Controllers;

use Spark\Billable;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Cashier\Subscription;
use Spark\HandlesCouponExceptions;
use Stripe\Exception\InvalidRequestException;

class ApplyCouponController
{
    use HandlesCouponExceptions;
    use RetrievesBillableModels;

    /**
     * Update the receipt emails for the given billable.
     *
     * @param  \Illuminate\Http\Request
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(Request $request): void
    {
        $billable = $this->billable();

        $request->validate([
            'coupon' => ['required'],
        ]);

        $subscription = $billable->subscription();

        try {
            $this->applyCoupon($request->coupon, $billable, $subscription);

            session(['spark.flash.success' => __('Coupon applied successfully.')]);
        } catch (InvalidRequestException $e) {
            if (in_array($e->getStripeParam(), ['coupon', 'promotion_code'])) {
                return $this->handleCouponException($e);
            }

            report($e);

            throw ValidationException::withMessages([
                '*' => __('An unexpected error occurred and we have notified our support team. Please try again later.'),
            ]);
        }
    }

    protected function applyCoupon(string $coupon, Billable $billable, ?Subscription $subscription): void
    {
        $codes = $billable->stripe()->promotionCodes->all(['code' => $coupon]);

        if ($codes && $codes->first()) {
            $subscription->updateStripeSubscription([
                'promotion_code' => $codes->first()->id,
            ]);
        } else {
            $subscription->updateStripeSubscription([
                'coupon' => $coupon,
            ]);
        }
    }
}
