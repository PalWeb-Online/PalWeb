<?php

namespace Spark\Actions;

use Illuminate\Validation\ValidationException;
use Spark\Concerns\HandlesPaymentFailures;
use Spark\Contracts\Actions\UpdatesSubscriptions;
use Spark\Spark;
use Stripe\Exception\ApiErrorException;

class UpdateSubscription implements UpdatesSubscriptions
{
    use HandlesPaymentFailures;

    /**
     * {@inheritDoc}
     */
    public function update($subscription, $plan)
    {
        $subscription->errorIfPaymentFails();

        $this->payPendingPayments($subscription);

        $subscription->setProrationBehavior(Spark::prorationBehavior());

        $this->attemptPayment(fn () => $subscription->swap($plan));
    }

    /**
     * Attempt to pay failed payments if any.
     *
     * @param  \Laravel\Cashier\Subscription  $subscription
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function payPendingPayments($subscription)
    {
        if (! $payment = $subscription->latestPayment()) {
            return;
        }

        $invoice = $subscription->latestInvoice();

        if (! $payment->isSucceeded() && $invoice->isOpen()) {
            try {
                $invoice->asStripeInvoice()->pay();
            } catch (ApiErrorException) {
                throw ValidationException::withMessages([
                    '*' => __('Your card was declined. Please contact your card issuer for more information.'),
                ]);
            }
        }
    }
}
