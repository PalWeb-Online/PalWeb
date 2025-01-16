<?php

namespace Spark;

use Spark\Plan;

class BillableConfigurationBuilder
{
    /**
     * The billable's type.
     *
     * @var string
     */
    protected $type;

    /**
     * Create a new billable configuration builder instance.
     *
     * @return void
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Register the callback that should be used to resolve the billable type.
     */
    public function resolve(callable $callback): static
    {
        Spark::resolveBillableUsing($this->type, $callback);

        return $this;
    }

    /**
     * Register the callback that should be used to authorize viewing the billing portal for the billable type.
     */
    public function authorize(callable $callback): static
    {
        Spark::authorizeUsing($this->type, $callback);

        return $this;
    }

    /**
     * Register the callback that should be used to determine plan eligibility.
     */
    public function checkPlanEligibility(callable $callback): static
    {
        Spark::checkPlanEligibilityUsing($this->type, $callback);

        return $this;
    }

    /**
     * Indicate that the application should charge the billable per seat.
     */
    public function chargePerSeat(string $seatName, callable $callback): static
    {
        Spark::chargePerSeat($this->type, $seatName, $callback);

        return $this;
    }

    /**
     * Define a subscription plan.
     */
    public function plan(string $name, int $id): Plan
    {
        return Spark::plan($this->type, $name, $id);
    }
}
