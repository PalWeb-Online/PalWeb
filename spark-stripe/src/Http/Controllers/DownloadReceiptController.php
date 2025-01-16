<?php

namespace Spark\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Symfony\Component\HttpFoundation\Response;

class DownloadReceiptController
{
    use RetrievesBillableModels;

    /**
     * Download the given receipt.
     */
    public function __invoke(Request $request, string $type, string $id, string $receiptId): Response
    {
        $billable = $this->billable($type, $id);

        $receiptData = array_merge([
            'vendor' => 'Laravel',
            'product' => '',
            'street' => '',
            'location' => '',
            'vat' => new HtmlString(nl2br(e($billable->extra_billing_information))),
        ], config('spark.receipt_data'));

        return $billable->downloadInvoice($receiptId, $receiptData);
    }
}
