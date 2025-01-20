<?php

namespace Spark;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection plans($billableType)
 * @method static \Spark\BillableConfigurationBuilder billable(string $class)
 * @method static \Spark\Billable|null resolveBillable($type, \Illuminate\Http\Request $request)
 * @method static \Spark\Plan plan($billableType, $name, $id)
 * @method static bool chargesPerSeat($billableType)
 * @method static bool isAuthorizedToViewBillingPortal($billable, \Illuminate\Http\Request $request)
 * @method static bool runsMigrations()
 * @method static int seatCount($billableType, $billable)
 * @method static string seatName($billableType)
 * @method static string billableModel($billableType)
 * @method static string prorationBehavior()
 * @method static void authorizeUsing($type, $callback)
 * @method static void chargePerSeat($billableType, $seatName, $callback)
 * @method static void checkPlanEligibilityUsing($type, $callback)
 * @method static void ensurePlanEligibility($billable, $plan)
 * @method static void checkoutSessionOptions($billableType, $callback)
 * @method static void ignoreMigrations()
 * @method static void resolveBillableUsing($type, $callback)
 *
 * @see \Spark\SparkManager
 */
class Spark extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'spark.manager';
    }
}
