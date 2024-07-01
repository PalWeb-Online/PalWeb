<?php

namespace Spark;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class SparkManager
{
    /**
     * Indicates if Spark migrations will be run.
     *
     * @var bool
     */
    public $runsMigrations = true;

    /**
     * The callback that determines the current billable.
     *
     * @var array
     */
    public $billableResolvingCallbacks = [];

    /**
     * The callbacks used for authorization.
     *
     * @var array
     */
    public $authorizationCallbacks = [];

    /**
     * The callbacks used to determine plan eligibility.
     *
     * @var array
     */
    public $planEligibilityCallbacks = [];

    /**
     * The word that describes what a "seat" is.
     *
     * @var string[]
     */
    public $seatNames = [];

    /**
     * The callback that determines how many seats are occupied.
     *
     * @var array
     */
    public $seatCountCallbacks = [];

    /**
     * All of the plans defined for the application.
     *
     * @var array
     */
    public $plans = [];

    /**
     * The registered callbacks that return the options for a Stripe Checkout Session.
     *
     * @var array
     */
    public $checkoutOptionsCallback = [];

    /**
     * Get a billable configuration builder for the given class.
     *
     * @return \Spark\BillableConfigurationBuilder
     */
    public static function billable(string $class)
    {
        foreach (config('spark.billables') as $type => $config) {
            if (Arr::get($config, 'model') == $class) {
                return new BillableConfigurationBuilder($type);
            }
        }

        throw new InvalidArgumentException("No billable has been defined for the [{$class}] model.");
    }

    /**
     * Set a callback to be used for resolving the billable.
     *
     * @param  string  $type
     * @param  callable  $callback
     * @return void
     */
    public function resolveBillableUsing($type, $callback)
    {
        $this->billableResolvingCallbacks[$type] = $callback;
    }

    /**
     * Resolve the current billable.
     *
     * @param  string  $type
     * @return \Spark\Billable|null
     */
    public function resolveBillable($type, Request $request)
    {
        if (isset($this->billableResolvingCallbacks[$type])) {
            return call_user_func($this->billableResolvingCallbacks[$type], $request);
        }
    }

    /**
     * Set a callback to be used for authorization.
     *
     * @param  string  $type
     * @param  callable  $callback
     * @return void
     */
    public function authorizeUsing($type, $callback)
    {
        $this->authorizationCallbacks[$type] = $callback;
    }

    /**
     * Determine if the billable is authroized to manage billing.
     *
     * @param  mixed  $billable
     * @return bool
     */
    public function isAuthorizedToViewBillingPortal($billable, Request $request)
    {
        $type = $billable->sparkConfiguration('type');

        if ($callback = $this->authorizationCallbacks[$type] ?? null) {
            if (! call_user_func($callback, $billable, $request)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Set a callback to be used to check plan eligibility.
     *
     * @param  string  $type
     * @param  callable  $callback
     * @return void
     */
    public function checkPlanEligibilityUsing($type, $callback)
    {
        $this->planEligibilityCallbacks[$type][] = $callback;
    }

    /**
     * Determine if the billable is eligible for the given plan.
     *
     * @param  mixed  $billable
     * @param  \Spark\Plan  $plan
     * @return void
     */
    public function ensurePlanEligibility($billable, $plan)
    {
        $checks = $this->planEligibilityCallbacks[$billable->sparkConfiguration('type')] ?? [];

        foreach ($checks as $callback) {
            call_user_func($callback, $billable, $plan);
        }
    }

    /**
     * Indicate that the application should charge billables per seat.
     *
     * @param  string  $billableType
     * @param  string  $seatName
     * @param  callable  $callback
     * @return void
     */
    public function chargePerSeat($billableType, $seatName, $callback)
    {
        $this->seatNames[$billableType] = $seatName;
        $this->seatCountCallbacks[$billableType] = $callback;
    }

    /**
     * The number of seats the billable occupies.
     *
     * @param  string  $billableType
     * @param  mixed  $billable
     * @return int
     */
    public function seatCount($billableType, $billable)
    {
        return call_user_func($this->seatCountCallbacks[$billableType], $billable);
    }

    /**
     * The word that describes what a "seat" is.
     *
     * @param  string  $billableType
     * @return string|null
     */
    public function seatName($billableType)
    {
        return $this->seatNames[$billableType] ?? null;
    }

    /**
     * Determine if the application should charge billables per seat.
     *
     * @param  string  $billableType
     * @return bool
     */
    public function chargesPerSeat($billableType)
    {
        return isset($this->seatCountCallbacks[$billableType]);
    }

    /**
     * Define a subscription plan.
     *
     * @param  string  $billableType
     * @param  string  $name
     * @param  int  $id
     * @return \Spark\Plan
     */
    public function plan($billableType, $name, $id)
    {
        $this->plans[$billableType][] = $plan = new Plan($name, $id);

        return $plan;
    }

    /**
     * Get all of the user defined plans.
     *
     * @param  string  $billableType
     * @return \Illuminate\Support\Collection
     */
    public function plans($billableType)
    {
        if (isset($this->plans[$billableType])) {
            return collect($this->plans[$billableType]);
        }

        return $this->plans[$billableType] = $this->toPlans(
            config("spark.billables.$billableType.plans")
        );
    }

    /**
     * Convert the given plans configuration to a Collection instance.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function toPlans(array $config)
    {
        $plans = collect();

        foreach (collect($config) as $plan) {
            foreach (['monthly', 'yearly'] as $interval) {
                if (! $id = $plan[$interval.'_id'] ?? null) {
                    continue;
                }

                $plans->push(
                    (new Plan($plan['name'], $id))
                        ->interval($interval)
                        ->incentive($plan['monthly_incentive'] ?? '', $plan['yearly_incentive'] ?? '')
                        ->shortDescription($plan['short_description'])
                        ->features($plan['features'])
                        ->options($plan['options'] ?? [])
                        ->trialDays($plan['trial_days'] ?? null)
                        ->status(isset($plan['archived']) ? ! $plan['archived'] : true)
                );
            }
        }

        return $plans;
    }

    /**
     * Get the application's subscription proration behaviour.
     *
     * @return string
     */
    public function prorationBehavior()
    {
        if (! is_null(config('spark.proration_behavior'))) {
            return config('spark.proration_behavior');
        } elseif (! config('spark.prorates')) {
            return 'none';
        }

        return 'always_invoice';
    }

    /**
     * Set the callback that should be used to determine the Stripe checkout session options.
     *
     * @param  string  $billableType
     * @param  callable  $callback
     * @return void
     */
    public function checkoutSessionOptions($billableType, $callback)
    {
        $this->checkoutOptionsCallback[$billableType] = $callback;
    }

    /**
     * Get the callback that should be used to determine the Stripe checkout session options.
     *
     * @param  string  $billableType
     * @return callable|null
     */
    public function getCheckoutSessionOptions($billableType)
    {
        return $this->checkoutOptionsCallback[$billableType] ?? null;
    }

    /**
     * Get the billable model class name for a given billable type.
     *
     * @param  string  $billableType
     * @return string
     */
    public function billableModel($billableType)
    {
        return config("spark.billables.$billableType.model");
    }

    /**
     * Configure Spark to not register its migrations.
     *
     * @return void
     */
    public function ignoreMigrations()
    {
        $this->runsMigrations = false;
    }

    /**
     * Determine if Spark should run its migrations.
     *
     * @return bool
     */
    public function runsMigrations()
    {
        return $this->runsMigrations;
    }
}
