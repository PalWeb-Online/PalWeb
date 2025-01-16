<?php

namespace Spark\Actions;

use Spark\Plan;
use Spark\Billable;
use Illuminate\Support\Carbon;
use Laravel\Cashier\SubscriptionBuilder;
use Spark\Contracts\Actions\CreatesSubscriptions;
use Spark\Features;
use Spark\Spark;
use Stripe\Subscription;
use Throwable;

class CreateSubscription implements CreatesSubscriptions
{
    /**
     * {@inheritDoc}
     */
    public function create($billable, $plan, array $options = [])
    {
        $type = $billable->sparkConfiguration('type');

        $planObject = Spark::plans($type)
            ->where('id', $plan)
            ->first();

        $sessionOptions = [];

        $this->purgeOldSubscriptions($billable);

        $builder = $billable->newSubscription('default', $plan);

        $this->configureTrial($billable, $planObject, $builder);

        if (isset($options['coupon']) && $options['coupon']) {
            $this->applyCoupon($options['coupon'], $billable, $builder);
        }

        if (Spark::chargesPerSeat($type)) {
            $builder->quantity(Spark::seatCount($type, $billable));
        }

        if (is_callable($sessionOptionsCallback = Spark::getCheckoutSessionOptions($type))) {
            $sessionOptions = $sessionOptionsCallback($billable, $planObject);
        }

        return $builder->checkout(array_merge(array_filter([
            'billing_address_collection' => Features::collectsBillingAddress() ? 'required' : null,
            'success_url' => route('spark.portal').'?checkout=subscription_started',
            'cancel_url' => route('spark.portal').'?checkout=cancelled',
        ]), $sessionOptions));
    }

    /**
     * Cancel and delete any old subscriptions except ones that were already cancelled.
     */
    protected function purgeOldSubscriptions(Billable $billable): void
    {
        $billable->subscriptions()->where('stripe_status', '!=', Subscription::STATUS_CANCELED)
            ->each(function ($subscription) {
                try {
                    $status = $subscription->stripe_status;

                    $subscription->noProrate();

                    $subscription->cancelNow();

                    if ($status === Subscription::STATUS_INCOMPLETE_EXPIRED) {
                        $subscription->items()->delete();
                        $subscription->delete();
                    }
                } catch (Throwable $e) {
                    //
                }
            });
    }

    /**
     * Configure the trial period.
     */
    protected function configureTrial(Billable $billable, Plan $plan, SubscriptionBuilder $builder): void
    {
        $skipTrialIfSubscribedBefore = config('spark.skip_trial_if_subscribed_before');

        if (is_null($skipTrialIfSubscribedBefore) || ! $subscription = $billable->subscription()) {
            if ($plan->trialDays) {
                $builder->trialUntil(Carbon::now()->addDays($plan->trialDays)->addHour());
            }

            return;
        }

        if (now()->diffInDays($subscription->ends_at) < $skipTrialIfSubscribedBefore) {
            $builder->skipTrial();

            return;
        }

        if ($plan->trialDays) {
            $builder->trialUntil(Carbon::now()->addDays($plan->trialDays)->addHour());
        }
    }

    /**
     * Apply the coupon or promocode.
     *
     *
     * @throws \Stripe\Exception\ApiErrorException
     */
    protected function applyCoupon(string $coupon, Billable $billable, SubscriptionBuilder $builder): void
    {
        $codes = $billable->stripe()->promotionCodes->all(['code' => $coupon]);

        if ($codes && $codes->first()) {
            $builder->withPromotionCode($codes->first()->id);
        } else {
            $builder->withCoupon($coupon);
        }
    }
}
