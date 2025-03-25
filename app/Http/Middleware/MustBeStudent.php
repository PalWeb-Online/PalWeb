<?php

namespace App\Http\Middleware;

use App\Events\UserDeniedAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustBeStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()->isAdmin() && ! $request->user()->isStudent()) {
            event(new UserDeniedAccess);
            return to_route('denied');
        }

        return $next($request);
    }
}
