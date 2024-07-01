<?php

namespace Spark\Http\Controllers;

use Illuminate\Http\Request;

class UpdateBillingInformationController
{
    use RetrievesBillableModels;

    /**
     * Update the additional billing information for the given billable.
     *
     * @param  \Illuminate\Http\Request
     * @return void
     */
    public function __invoke(Request $request)
    {
        $billable = $this->billable();

        $billable->forceFill([
            'extra_billing_information' => $request->extra_billing_information,
        ])->save();

        session(['spark.flash.success' => __('Additional billing information updated successfully.')]);
    }
}
