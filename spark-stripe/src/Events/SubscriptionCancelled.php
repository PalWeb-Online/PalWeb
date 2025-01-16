<?php

namespace Spark\Events;

use Spark\Billable;
use Laravel\Cashier\Subscription;

class SubscriptionCancelled
{
    /**
     * The billable instance.
     *
     * @var \Spark\Billable
     */
    public $billable;

    /**
     * The subscription model.
     *
     * @var \Laravel\Cashier\Subscription
     */
    public $subscription;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Billable $billable, Subscription $subscription)
    {
        $this->billable = $billable;
        $this->subscription = $subscription;
    }
}
