<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * This middleware fetches the language from a user session and passes it to the Locale facade
 * be rendered
 */
class SetSiteLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lang = 'en';
        $auth = $request->user();
        if ($auth) {
            // Log::info("set site locale setting to user " . $auth->name . " " . $auth->language);
            App::setLocale($auth->language);
        } elseif ($request->session()->has('language')) {
            $lang = $request->session()->get('language');
            App::setLocale($lang);
        }

        return $next($request);
    }
}
