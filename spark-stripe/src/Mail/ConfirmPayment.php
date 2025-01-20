<?php

namespace Spark\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Laravel\Cashier\Payment;

class ConfirmPayment extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * The payment ID.
     *
     * @var string
     */
    public $paymentId;

    /**
     * The payment amount.
     *
     * @var string
     */
    public $amount;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->paymentId = $payment->id;
        $this->amount = $payment->amount();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = route('cashier.payment', ['id' => $this->paymentId]);

        return $this->markdown('spark::mail.confirm_payment', compact('url'))
            ->subject(__('Confirm Payment'));
    }
}
