<?php

use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \SocialiteProviders\Manager\ServiceProvider::class,
        \Spark\SparkServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        // channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(AppServiceProvider::HOME);
        $middleware->redirectUsersTo(AppServiceProvider::HOME);

        $middleware->web([
            \App\Http\Middleware\SetSiteLocale::class,
            \App\Http\Middleware\TrackPageViews::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);

        $middleware->throttleApi();

        $middleware->alias([
            'admin' => \App\Http\Middleware\MustBeAdmin::class,
            'student' => \App\Http\Middleware\MustBeStudent::class,
            'pageDescription' => \App\Http\Middleware\PageDescription::class,
            'pageTitle' => \App\Http\Middleware\PageTitle::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            if (! app()->environment(['local', 'testing']) && in_array($response->getStatusCode(), [500, 503, 404, 403])) {
                Inertia::setRootView('layouts.app');
                return Inertia::render('Error', [
                    'section' => 'community',
                    'status' => $response->getStatusCode()
                ])
                    ->toResponse($request)
                    ->setStatusCode($response->getStatusCode());

            } elseif ($response->getStatusCode() === 419) {
                session()->flash('notification', ['type' => 'error', 'message' => 'The page expired, please try again.']);
                return back();
            }

            return $response;
        });
    })->create();
