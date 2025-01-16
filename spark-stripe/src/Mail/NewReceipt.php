<?php

namespace Spark\Mail;

use Laravel\Cashier\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\HtmlString;

class NewReceipt extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * The available invoice.
     *
     * @var \Laravel\Cashier\Invoice
     */
    public $invoice;

    /**
     * The billable model instance.
     *
     * @var \Spark\Billable
     */
    private $billable;

    /**
     * Create a new message instance.
     *
     * @param  mixed  $billable
     * @return void
     */
    public function __construct($billable, Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->billable = $billable;
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        $receiptData = array_merge([
            'vendor' => 'Laravel',
            'product' => '',
            'street' => '',
            'location' => '',
            'vat' => new HtmlString(nl2br(e($this->billable->extra_billing_information))),
        ], config('spark.receipt_data'));

        $filename = $receiptData['product'].'_'.$this->invoice->date()->month.'_'.$this->invoice->date()->year;

        return $this->markdown('spark::mail.receipt')
            ->subject(__('Your :invoiceName invoice is now available!', ['invoiceName' => $this->invoice->date()->format('F Y')]))
            ->attachData($this->invoice->pdf($receiptData), $filename.'.pdf');
    }
}
