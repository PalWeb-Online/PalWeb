<?php

namespace Spark\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Spark\Contracts\Actions\PaysInvoices;

class PayInvoiceController
{
    use RetrievesBillableModels;

    /**
     * Pay the open invoice.
     */
    public function __invoke($invoiceId): ?JsonResponse
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
