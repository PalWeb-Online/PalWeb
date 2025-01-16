<?php

namespace Spark;

use Illuminate\Support\Carbon;
use Laravel\Cashier\Billable as CashierBillable;
use Laravel\Cashier\Jobs\SyncCustomerDetails;
use Spark\Contracts\Actions\CalculatesVatRate;

trait Billable
{
    use CashierBillable;

    /**
     * Boot the billable model.
     *
     * @return void
     */
    public static function bootBillable()
    {
        static::created(function ($model) {
            $trialDays = $model->sparkConfiguration('trial_days');

            $model->forceFill([
                'trial_ends_at' => $trialDays ? now()->addDays($trialDays) : null,
            ])->save();
        });

        static::updated(function ($customer) {
            if ($customer->hasStripeId() && $customer->shouldSyncCustomerDetailsToStripe()) {
                dispatch(new SyncCustomerDetails($customer));
            }
        });
    }

    /**
     * Determine if any Stripe synced customer data has changed.
     *
     * @return bool
     */
    public function shouldSyncCustomerDetailsToStripe()
    {
        return config('cashier.secret') &&
            ! env('CI') &&
            ! app()->runningUnitTests() &&
            $this->wasChanged($this->stripeAttributes());
    }

    /**
     * The model attributes that will trigger a Stripe customer update.
     *
     * @return array
     */
    protected function stripeAttributes()
    {
        return [
            'name',
            'email',
            'phone',
            'billing_address',
            'billing_address_line_2',
            'billing_city',
            'billing_state',
            'billing_postal_code',
            'billing_country',
        ];
    }

    /**
     * Get the address that should be synced to Stripe.
     *
     * @return array|null
     */
    public function stripeAddress()
    {
        return [
            'line1' => $this->billing_address,
            'line2' => $this->billing_address_line_2,
            'city' => $this->billing_city,
            'state' => $this->billing_state,
            'postal_code' => $this->billing_postal_code,
            'country' => $this->billing_country,
        ];
    }

    /**
     * Get all of the local receipts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * @deprecated This method will be removed in a future Spark release.
     */
    public function localReceipts()
    {
        return $this->hasMany(Receipt::class, $this->getForeignKey())->orderBy('id', 'desc');
    }

    /**
     * Get the Spark plan that corresponds with the billable's current subscription.
     *
     * @return \Spark\Plan|null
     */
    public function sparkPlan()
    {
        $subscription = $this->subscription();

        $plans = Spark::plans($this->sparkConfiguration('type'));

        if ($subscription && $subscription->valid()) {
            return $plans->first(function ($plan) use ($subscription) {
                return $plan->id == $subscription->stripe_price;
            });
        }
    }

    /**
     * Get the Spark configuration or a configuration item for the billable model.
     *
     * @param  string|null  $key
     * @return mixed
     */
    public function sparkConfiguration($key = null)
    {
        $config = collect(config('spark.billables'))->map(function ($config, $type) {
            $config['type'] = $type;

            return $config;
        })->first(function ($billable, $type) {
            return $billable['model'] == get_class($this);
        });

        if ($key) {
            return $config[$key] ?? null;
        }

        return $config;
    }

    /**
     * Add seats to the current subscription.
     *
     * @param  int  $count
     * @return void
     *
     * @throws \Stripe\Exception\CardException
     * @throws \Laravel\Cashier\Exceptions\IncompletePayment
     * @throws \Laravel\Cashier\Exceptions\SubscriptionUpdateFailure
     */
    public function addSeat($count = 1)
    {
        if (! $subscription = $this->subscription()) {
            return;
        }

        $subscription
            ->errorIfPaymentFails()
            ->setProrationBehavior(Spark::prorationBehavior())
            ->incrementQuantity($count);
    }

    /**
     * Remove seats from the current subscription.
     *
     * @param  int  $count
     * @return void
     */
    public function removeSeat($count = 1)
    {
        if (! $subscription = $this->subscription()) {
            return;
        }

        $subscription
            ->errorIfPaymentFails()
            ->setProrationBehavior(Spark::prorationBehavior())
            ->decrementQuantity($count);
    }

    /**
     * Update the number of seats in the current subscription.
     *
     * @param  int  $count
     * @return void
     *
     * @throws \Stripe\Exception\CardException
     * @throws \Laravel\Cashier\Exceptions\IncompletePayment
     * @throws \Laravel\Cashier\Exceptions\SubscriptionUpdateFailure
     */
    public function updateSeats($count)
    {
        if (! $subscription = $this->subscription()) {
            return;
        }

        $subscription
            ->errorIfPaymentFails()
            ->setProrationBehavior(Spark::prorationBehavior())
            ->updateQuantity($count);
    }

    /**
     * Get the receipt emails.
     *
     * @param  mixed  $value
     * @return array
     */
    public function getReceiptEmailsAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }

        return json_decode($value) ?: [];
    }

    /**
     * Fills the model's properties with the payment method from Stripe.
     *
     * @param  \Laravel\Cashier\PaymentMethod|\Stripe\PaymentMethod|null  $paymentMethod
     * @return $this
     */
    protected function fillPaymentMethodDetails($paymentMethod)
    {
        if ($paymentMethod->type === 'card') {
            $this->pm_type = $paymentMethod->card->brand;
            $this->pm_last_four = $paymentMethod->card->last4;
            $this->pm_expiration = sprintf('%02d', $paymentMethod->card->exp_month).'/'.$paymentMethod->card->exp_year;
        } else {
            $this->pm_type = $type = $paymentMethod->type;
            $this->pm_last_four = $paymentMethod?->$type->last4;
            $this->pm_expiration = null;
        }

        return $this;
    }

    /**
     * Determine if the Stripe model is on a "generic" trial at the model level.
     *
     * @return bool
     */
    public function onGenericTrial()
    {
        if (! $this->trial_ends_at) {
            return;
        }

        if ($this->hasCast('trial_ends_at')) {
            return $this->trial_ends_at->isFuture();
        }

        return Carbon::createFromFormat('Y-m-d H:i:s', $this->trial_ends_at)->isFuture();
    }

    /**
     * Get the time when the generic trial ends.
     *
     * @return \Carbon\Carbon|null
     */
    public function genericTrialEndsAt()
    {
        if (! $this->trial_ends_at) {
            return;
        }

        if ($this->hasCast('trial_ends_at')) {
            return $this->trial_ends_at;
        }

        return Carbon::createFromFormat('Y-m-d H:i:s', $this->trial_ends_at);
    }

    /**
     * Get the tax rates to apply to the subscription.
     *
     * @return array
     */
    public function taxRates()
    {
        if (! Features::collectsEuVat()) {
            return null;
        }

        $homeCountry = is_string(config('spark.collects_eu_vat'))
            ? config('spark.collects_eu_vat')
            : Features::option('eu-vat-collection', 'home-country');

        $rate = app(CalculatesVatRate::class)->calculate(
            $homeCountry,
            $this->billing_country,
            $this->billing_postal_code,
            $this->vat_id,
        );

        if ($rate == 0) {
            return null;
        }

        if ($existing = TaxRate::where('percentage', $rate)->first()) {
            return [$existing->stripe_id];
        }

        $stripeTaxRate = $this->stripe()->taxRates->create([
            'display_name' => 'VAT',
            'inclusive' => false,
            'percentage' => $rate,
        ]);

        TaxRate::create([
            'stripe_id' => $stripeTaxRate->id,
            'percentage' => $rate,
        ]);

        return [$stripeTaxRate->id];
    }
}
