<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use App\Events\UserViewed;
use Closure;
use Illuminate\Http\Request;

/**
 * Used to track the individual page views of each user
 */
class TrackPageViews
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.track_page_views')) {
            // Fire off an event log the page view
            event(new UserViewed);
        }

        return $next($request);
    }
}
