<?php

namespace Spark;

use Spark\BillableConfigurationBuilder;
use Spark\Billable;
use Spark\Plan;
use Illuminate\Support\Collection;
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
     */
    public static function billable(string $class): BillableConfigurationBuilder
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
     */
    public function resolveBillableUsing(string $type, callable $callback): void
    {
        $this->billableResolvingCallbacks[$type] = $callback;
    }

    /**
     * Resolve the current billable.
     */
    public function resolveBillable(string $type, Request $request): ?Billable
    {
        if (isset($this->billableResolvingCallbacks[$type])) {
            return call_user_func($this->billableResolvingCallbacks[$type], $request);
        }
    }

    /**
     * Set a callback to be used for authorization.
     */
    public function authorizeUsing(string $type, callable $callback): void
    {
        $this->authorizationCallbacks[$type] = $callback;
    }

    /**
     * Determine if the billable is authroized to manage billing.
     *
     * @param  mixed  $billable
     */
    public function isAuthorizedToViewBillingPortal($billable, Request $request): bool
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
     */
    public function checkPlanEligibilityUsing(string $type, callable $callback): void
    {
        $this->planEligibilityCallbacks[$type][] = $callback;
    }

    /**
     * Determine if the billable is eligible for the given plan.
     *
     * @param  mixed  $billable
     */
    public function ensurePlanEligibility($billable, Plan $plan): void
    {
        $checks = $this->planEligibilityCallbacks[$billable->sparkConfiguration('type')] ?? [];

        foreach ($checks as $callback) {
            call_user_func($callback, $billable, $plan);
        }
    }

    /**
     * Indicate that the application should charge billables per seat.
     */
    public function chargePerSeat(string $billableType, string $seatName, callable $callback): void
    {
        $this->seatNames[$billableType] = $seatName;
        $this->seatCountCallbacks[$billableType] = $callback;
    }

    /**
     * The number of seats the billable occupies.
     *
     * @param  mixed  $billable
     */
    public function seatCount(string $billableType, $billable): int
    {
        return call_user_func($this->seatCountCallbacks[$billableType], $billable);
    }

    /**
     * The word that describes what a "seat" is.
     */
    public function seatName(string $billableType): ?string
    {
        return $this->seatNames[$billableType] ?? null;
    }

    /**
     * Determine if the application should charge billables per seat.
     */
    public function chargesPerSeat(string $billableType): bool
    {
        return isset($this->seatCountCallbacks[$billableType]);
    }

    /**
     * Define a subscription plan.
     */
    public function plan(string $billableType, string $name, int $id): Plan
    {
        $this->plans[$billableType][] = $plan = new Plan($name, $id);

        return $plan;
    }

    /**
     * Get all of the user defined plans.
     */
    public function plans(string $billableType): Collection
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
     */
    protected function toPlans(array $config): Collection
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
     */
    public function prorationBehavior(): string
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
     */
    public function checkoutSessionOptions(string $billableType, callable $callback): void
    {
        $this->checkoutOptionsCallback[$billableType] = $callback;
    }

    /**
     * Get the callback that should be used to determine the Stripe checkout session options.
     */
    public function getCheckoutSessionOptions(string $billableType): ?callable
    {
        return $this->checkoutOptionsCallback[$billableType] ?? null;
    }

    /**
     * Get the billable model class name for a given billable type.
     */
    public function billableModel(string $billableType): string
    {
        return config("spark.billables.$billableType.model");
    }

    /**
     * Configure Spark to not register its migrations.
     */
    public function ignoreMigrations(): void
    {
        $this->runsMigrations = false;
    }

    /**
     * Determine if Spark should run its migrations.
     */
    public function runsMigrations(): bool
    {
        return $this->runsMigrations;
    }
}
