<?php

namespace Spark\Http\Controllers;

use Spark\Billable;
use Spark\Spark;

trait RetrievesBillableModels
{
    /**
     * Get the billable model for the request.
     *
     * @param  string  $type
     * @param  mixed  $id
     * @return \Illuminate\Database\Eloquent\Model|\Spark\Billable
     */
    public function billable($type = null, $id = null)
    {
        $type = $type ?: request('billableType');

        $id = $id ?: request('billableId');

        if (! $id && $defaultBillable = Spark::resolveBillable($type, request())) {
            $id = $defaultBillable->id;
        }

        if (! Spark::billableModel($type) || ! $billable = Spark::billableModel($type)::find($id)) {
            abort(404);
        }

        if (! Spark::isAuthorizedToViewBillingPortal($billable, request())) {
            abort(403);
        }

        if (! in_array(Billable::class, class_uses_recursive($billable))) {
            throw new \RuntimeException('Class ['.get_class($billable).'] does not use the [Spark\Billable] trait.');
        }

        return $billable;
    }
}
