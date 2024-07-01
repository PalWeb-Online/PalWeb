<?php

namespace Spark\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

class DownloadReceiptController
{
    use RetrievesBillableModels;

    /**
     * Download the given receipt.
     *
     * @param  string  $type
     * @param  string  $id
     * @param  string  $receiptId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request, $type, $id, $receiptId)
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
