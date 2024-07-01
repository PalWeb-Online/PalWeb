<?php

namespace Spark\Concerns;

use Illuminate\Validation\ValidationException;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Spark\HandlesCouponExceptions;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;
use Throwable;

trait HandlesPaymentFailures
{
    use HandlesCouponExceptions;

    /**
     * Attempt a payment and handle any failure that would occur.
     *
     * @return mixed
     */
    public function attemptPayment(callable $callback)
    {
        try {
            return $callback();
        } catch (CardException $e) {
            throw ValidationException::withMessages([
                '*' => __('Your card was declined. Please contact your card issuer for more information.'),
            ]);
        } catch (InvalidRequestException $e) {
            if (in_array($e->getStripeParam(), ['coupon', 'promotion_code'])) {
                return $this->handleCouponException($e);
            }

            report($e);

            throw ValidationException::withMessages([
                '*' => __('We are unable to process your payment. Please contact customer support.'),
            ]);
        } catch (IncompletePayment $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);

            throw ValidationException::withMessages([
                '*' => __('We are unable to process your payment. Please contact customer support.'),
            ]);
        }
    }
}
