<?php

namespace Spark\Http\Controllers;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Laravel\Cashier\Invoice;
use Laravel\Cashier\Payment;
use Spark\Events\PaymentSucceeded;
use Spark\Events\SubscriptionCancelled;
use Spark\Events\SubscriptionCreated;
use Spark\Events\SubscriptionUpdated;
use Spark\Features;
use Spark\Mail\ConfirmPayment;
use Spark\Mail\NewReceipt;
use Stripe\Subscription;

class WebhookController extends CashierController
{
    /**
     * {@inheritDoc}
     */
    protected function handleCustomerSubscriptionUpdated(array $payload)
    {
        if ($billable = $this->getUserByStripeId($payload['data']['object']['customer'])) {
            $subscription = $billable->subscriptions()->where('stripe_id', $payload['data']['object']['id'])->first();

            $oldStatus = $subscription->stripe_status;

            $newStatus = $payload['data']['object']['status'] ?? null;

            parent::handleCustomerSubscriptionUpdated($payload);

            if ($newStatus &&
                $newStatus == Subscription::STATUS_ACTIVE &&
                ! in_array($oldStatus, [Subscription::STATUS_ACTIVE, Subscription::STATUS_TRIALING])) {
                event(new SubscriptionCreated($billable, $subscription->refresh()));

                $billable->update(['trial_ends_at' => null]);
            } else {
                event(new SubscriptionUpdated($billable, $subscription->refresh()));
            }
        }

        return $this->successMethod();
    }

    /**
     * {@inheritDoc}
     */
    protected function handleCustomerSubscriptionDeleted(array $payload)
    {
        if ($billable = $this->getUserByStripeId($payload['data']['object']['customer'])) {
            parent::handleCustomerSubscriptionDeleted($payload);

            $subscription = $billable->subscriptions()->where('stripe_id', $payload['data']['object']['id'])->first();

            if ($subscription) {
                event(new SubscriptionCancelled($billable, $subscription));

                if (config('spark.void_cancelled_subscription_invoices', false)) {
                    $subscription->invoicesIncludingPending()
                        ->where(fn (Invoice $invoice) => $invoice->isOpen())
                        ->each
                        ->void();
                }
            }
        }

        return $this->successMethod();
    }

    /**
     * {@inheritDoc}
     */
    protected function handleCustomerDeleted(array $payload)
    {
        if ($billable = $this->getUserByStripeId($payload['data']['object']['id'])) {
            parent::handleCustomerDeleted($payload);

            $subscription = $billable->subscriptions()->where('stripe_id', $payload['data']['object']['id'])->first();

            event(new SubscriptionCancelled($billable, $subscription));
        }

        return $this->successMethod();
    }

    /**
     * Handle a successful invoice payment event.
     *
     * @return \Illuminate\Http\Response
     */
    protected function handleInvoicePaymentSucceeded(array $payload)
    {
        if ($billable = $this->getUserByStripeId($payload['data']['object']['customer'])) {
            if ($invoice = $billable->findInvoice($payload['data']['object']['id'])) {
                if (! $billable->localReceipts()->where('provider_id', $invoice->id)->first()) {
                    $billable->localReceipts()->create([
                        'provider_id' => $invoice->id,
                        'amount' => Cashier::formatAmount($invoice->amount_due, $invoice->currency),
                        'tax' => $invoice->tax(),
                        'paid_at' => $invoice->date(),
                    ]);

                    $this->sendReceiptNotification($billable, $invoice);
                }

                event(new PaymentSucceeded($billable, $invoice));
            }
        }

        return $this->successMethod();
    }

    /**
     * Handle payment action required for invoice.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleInvoicePaymentActionRequired(array $payload)
    {
        if ($billable = $this->getUserByStripeId($payload['data']['object']['customer'])) {
            if (in_array(Notifiable::class, class_uses_recursive($billable))) {
                $payment = new Payment($billable->stripe()->paymentIntents->retrieve(
                    $payload['data']['object']['payment_intent']
                ));

                $this->sendPaymentConfirmationNotification($billable, $payment);
            }
        }

        return $this->successMethod();
    }

    /**
     * Send the receipt notification email.
     *
     * @param  \Spark\Billable  $billable
     * @param  \Laravel\Cashier\Invoice|null  $invoice
     * @return void
     */
    protected function sendReceiptNotification($billable, $invoice)
    {
        if (! config('spark.sends_receipt_emails') && ! Features::sendsReceiptEmails()) {
            return;
        }

        $mails = Features::optionEnabled('receipt-emails-sending', 'custom-addresses')
            ? $billable->receipt_emails : [];

        if (empty($mails)) {
            $mails = [$billable->stripeEmail()];
        }

        Mail::to($mails)->send(
            new NewReceipt($billable, $invoice)
        );
    }

    /**
     * Send the payment confirmation notification email.
     *
     * @param  \Spark\Billable  $billable
     * @param  \Laravel\Cashier\Payment  $payment
     * @return void
     */
    protected function sendPaymentConfirmationNotification($billable, $payment)
    {
        if (! config('spark.sends_payment_notification_emails') &&
            ! Features::sendsPaymentNotificationEmails()) {
            return;
        }

        Mail::to($billable->stripeEmail())->send(
            new ConfirmPayment($payment)
        );
    }
}
