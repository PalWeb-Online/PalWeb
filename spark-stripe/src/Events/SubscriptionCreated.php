<?php

namespace Spark\Events;

use Laravel\Cashier\Subscription;
use Spark\Billable;

class SubscriptionCreated
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
