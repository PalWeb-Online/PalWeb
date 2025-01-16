<?php

namespace Spark\Events;

use Spark\Billable;
use Laravel\Cashier\Invoice;

class AttemptingPayment
{
    /**
     * The billable instance.
     *
     * @var \Spark\Billable
     */
    public $billable;

    /**
     * The invoice instance.
     *
     * @var \Laravel\Cashier\Invoice
     */
    public $invoice;

    /**
     * Create a new event instance.
     *
     * @param  \Spark\Billable  $billable
     * @return void
     */
    public function __construct(Billable $billable, Invoice $invoice)
    {
        $this->billable = $billable;
        $this->invoice = $invoice;
    }
}
