<?php

namespace Spark\Http\Controllers;

use Laravel\Cashier\Exceptions\IncompletePayment;
use Spark\Contracts\Actions\PaysInvoices;

class PayInvoiceController
{
    use RetrievesBillableModels;

    /**
     * Pay the open invoice.
     *
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function __invoke($invoiceId)
    {
        $invoice = ($billable = $this->billable())->findInvoiceOrFail($invoiceId);

        try {
            app(PaysInvoices::class)->pay($billable, $invoice);
        } catch (IncompletePayment $e) {
            return response()->json([
                'paymentId' => $e->payment->id,
            ]);
        }
    }
}
