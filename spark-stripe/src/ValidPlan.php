<?php

namespace Spark;

use Illuminate\Contracts\Validation\ValidationRule;

class ValidPlan implements ValidationRule
{
    /**
     * The plan type.
     *
     * @var string
     */
    protected $type;

    /**
     * Create a new rule instance.
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Validate the attribute value.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  \Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $plan = Spark::plans($this->type)
            ->first(function ($plan) use ($value) {
                return $plan->id == $value;
            });

        if (is_null($plan) || ! $plan->active) {
            $fail(__('The selected plan is invalid.'));
        }
    }
}
