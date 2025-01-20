<?php

namespace Spark;

use Illuminate\Contracts\Validation\Rule;

class ValidPlan implements Rule
{
    /**
     * The plan type.
     *
     * @var string
     */
    protected $type;

    /**
     * Create a new rule instance.
     *
     * @param  string  $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $plan = Spark::plans($this->type)
            ->first(function ($plan) use ($value) {
                return $plan->id == $value;
            });

        return ! is_null($plan) && $plan->active;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The selected plan is invalid.');
    }
}
