<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetSiteLocale
{
    private const SUPPORTED_LOCALES = ['en', 'es', 'ar'];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        App::setLocale($this->resolveLocale($request));

        return $next($request);
    }

    private function resolveLocale(Request $request): string
    {
        $userLocale = $request->user()?->language;

        if ($this->isSupported($userLocale)) {
            return $userLocale;
        }

        $cookieLocale = $request->cookie('language');

        if ($this->isSupported($cookieLocale)) {
            return $cookieLocale;
        }

        return $request->getPreferredLanguage(self::SUPPORTED_LOCALES)
            ?? config('app.locale');
    }

    private function isSupported(?string $locale): bool
    {
        return in_array($locale, self::SUPPORTED_LOCALES, true);
    }
}
