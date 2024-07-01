<?php

namespace Spark;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Macroable;
use JsonSerializable;

class Plan implements Arrayable, JsonSerializable
{
    use Macroable;

    /**
     * The plan's Stripe ID.
     *
     * @var string
     */
    public $id;

    /**
     * The plan's displayable name.
     *
     * @var string
     */
    public $name;

    /**
     * The plan's interval.
     *
     * @var string
     */
    public $interval = 'monthly';

    /**
     * The number of trial days that come with the plan.
     *
     * @var int
     */
    public $trialDays;

    /**
     * The plan's price (if available).
     *
     * @var float
     */
    public $price;

    /**
     * The plan's currency (if available).
     *
     * @var string
     */
    public $currency;

    /**
     * The plan's price.
     *
     * @var int
     */
    public $rawPrice;

    /**
     * The plan's monthly incentive text.
     *
     * @var string
     */
    public $monthlyIncentive;

    /**
     * The plan's yearly incentive text.
     *
     * @var string
     */
    public $yearlyIncentive;

    /**
     * The plan's short description.
     *
     * @var string
     */
    public $shortDescription;

    /**
     * The plan's features.
     *
     * @var array
     */
    public $features = [];

    /**
     * The plan options.
     *
     * @var array
     */
    public $options;

    /**
     * Indicates if the plan is active.
     *
     * @var bool
     */
    public $active = true;

    /**
     * Create a new plan instance.
     *
     * @param  string  $name
     * @param  string  $id
     * @return void
     */
    public function __construct($name, $id)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Specify the plan's interval (monthly or yearly).
     *
     * @return $this
     */
    public function interval(string $interval)
    {
        $this->interval = $interval;

        return $this;
    }

    /**
     * Specify the plan's interval is monthly.
     *
     * @return $this
     */
    public function monthly()
    {
        $this->interval = 'monthly';

        return $this;
    }

    /**
     * Specify the plan's interval is yearly.
     *
     * @return $this
     */
    public function yearly()
    {
        $this->interval = 'yearly';

        return $this;
    }

    /**
     * Specify the number of trial days that come with the plan.
     *
     * @param  int  $trialDays
     * @return $this
     */
    public function trialDays($trialDays)
    {
        $this->trialDays = $trialDays;

        return $this;
    }

    /**
     * Set the incentive text for the plan.
     *
     * @return $this
     */
    public function incentive(string $monthlyIncentive, string $yearlyIncentive)
    {
        $this->monthlyIncentive = $monthlyIncentive;
        $this->yearlyIncentive = $yearlyIncentive;

        return $this;
    }

    /**
     * Set the short description of the plan.
     *
     * @return $this
     */
    public function shortDescription(string $description)
    {
        $this->shortDescription = $description;

        return $this;
    }

    /**
     * Specify the plan's features.
     *
     * @return $this
     */
    public function features(array $features)
    {
        $this->features = $features;

        return $this;
    }

    /**
     * Specify the plan's options.
     *
     * @return $this
     */
    public function options(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Set the active "status" of the plan.
     *
     * @return $this
     */
    public function status(bool $active = true)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Set the active "status" of the plan to archived.
     *
     * @return $this
     */
    public function archive()
    {
        $this->active = false;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'interval' => $this->interval,
            'price' => $this->price,
            'currency' => $this->currency,
            'raw_price' => $this->rawPrice,
            'incentive' => [
                'monthly' => $this->monthlyIncentive,
                'yearly' => $this->yearlyIncentive,
            ],
            'short_description' => $this->shortDescription,
            'trial_days' => $this->trialDays,
            'features' => $this->features,
            'options' => $this->options,
            'active' => $this->active,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
