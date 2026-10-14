<?php

use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        then: function () {
            Route::fallback(function (Request $request) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    abort(404);
                }

                return view('layouts.app');
            });
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(AppServiceProvider::HOME);
        $middleware->redirectUsersTo(AppServiceProvider::HOME);

        $middleware->web([
            \App\Http\Middleware\SetSiteLocale::class,
            \App\Http\Middleware\TrackPageViews::class,
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
            $status = $response->getStatusCode();

            if (app()->isProduction() && in_array($status, [500, 503])) {
                report($exception);
            }

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => match ($status) {
                        403 => 'Forbidden',
                        404 => 'Not Found',
                        419 => 'CSRF token mismatch.',
                        500 => 'Server error',
                        503 => 'Service unavailable',
                        default => $exception->getMessage(),
                    },
                    'status' => $status,
                ], $status);
            }

            if ($status === 419) {
                session()->flash('notification', ['type' => 'error', 'message' => 'The page expired, please try again.']);
                return redirect()->back();
            }

            return $response;
        });
    })->create();
