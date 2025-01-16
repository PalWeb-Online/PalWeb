<?php

namespace Spark\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Spark\GuessesBillableTypes;
use Spark\Spark;

class VerifyBillableIsSubscribed
{
    use GuessesBillableTypes;

    /**
     * Verify the incoming request's user has a subscription.
     */
    public function handle(Request $request, Closure $next, ?string $billableType = null, ?string $plan = null): Response
    {
        $billableType = $billableType ?: $this->guessBillableType($billableType);

        if ($this->subscribed($request, $billableType, $plan)) {
            return $next($request);
        }

        $redirect = $this->redirect($billableType);

        if ($request->header('X-Inertia')) {
            return Inertia::location($redirect);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response('Subscription Required.', 402);
        }

        return redirect($redirect);
    }

    /**
     * Determine if the given user is subscribed to the given plan.
     *
     * @param  bool  $defaultSubscription
     */
    protected function subscribed(Request $request, string $type, string $plan): bool
    {
        if (! $billable = Spark::resolveBillable($type, $request)) {
            return false;
        }

        $subscription = $billable->subscription();

        if ($plan && (! $subscription || $subscription->stripe_price != $plan)) {
            return false;
        }

        return ($subscription && $subscription->active()) ||
                $billable->onGenericTrial();
    }

    /**
     * Get the redirect location.
     */
    protected function redirect(string $billableType): string
    {
        $redirect = '/'.config('spark.path');

        if ($billableType != 'user') {
            $redirect .= '/'.$billableType;
        }

        return $redirect;
    }
}
