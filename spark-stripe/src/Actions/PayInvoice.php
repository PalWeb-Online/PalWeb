<?php

namespace Spark\Actions;

use Illuminate\Validation\ValidationException;
use Laravel\Cashier\Invoice;
use Spark\Concerns\HandlesPaymentFailures;
use Spark\Contracts\Actions\PaysInvoices;
use Spark\Events\AttemptingPayment;
use Spark\Events\PaymentAttempted;

class PayInvoice implements PaysInvoices
{
    use HandlesPaymentFailures;

    /**
     * Pay the invoice related to the given receipt.
     *
     * @param  \Spark\Billable  $billable
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Laravel\Cashier\Exceptions\IncompletePayment
     */
    public function pay($billable, Invoice $invoice)
    {
        if (! $invoice->isOpen()) {
            throw ValidationException::withMessages([
                '*' => __('This invoice is no longer open.'),
            ]);
        }

        event(new AttemptingPayment($billable, $invoice));

        $invoice = $this->attemptPayment(fn () => $invoice->pay());

        event(new PaymentAttempted($billable, $invoice));
    }
}
