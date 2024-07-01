<?php

namespace Spark;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Stripe\Exception\InvalidRequestException;

trait HandlesCouponExceptions
{
    /**
     * Get the default billable type for the application.
     *
     * @return void
     */
    protected function handleCouponException(InvalidRequestException $e)
    {
        if (Str::of($e->getMessage())->contains('customer has prior transactions')) {
            throw ValidationException::withMessages([
                '*' => __('This coupon code can only be used by new customers.'),
            ]);
        }

        throw ValidationException::withMessages([
            '*' => __('The provided coupon code is invalid.'),
        ]);
    }
}
