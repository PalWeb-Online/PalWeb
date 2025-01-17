<?php

namespace Spark;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use RuntimeException;
use Spark\Contracts\Actions\CalculatesVatRate;
use Spark\Contracts\Actions\CreatesSubscriptions;
use Spark\Contracts\Actions\PaysInvoices;
use Spark\Contracts\Actions\UpdatesSubscriptions;

class SparkServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__.'/../config/spark.php', 'spark');
        }

        $this->app->singleton('spark.manager', SparkManager::class);
        $this->app->singleton(FrontendState::class);

        $this->app->singleton(CalculatesVatRate::class, Actions\CalculateVatRate::class);
        $this->app->singleton(CreatesSubscriptions::class, Actions\CreateSubscription::class);
        $this->app->singleton(PaysInvoices::class, Actions\PayInvoice::class);
        $this->app->singleton(UpdatesSubscriptions::class, Actions\UpdateSubscription::class);

        $this->registerCommands();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (is_array(config('spark.billables')) && count(config('spark.billables')) > 1) {
            throw new RuntimeException('The Stripe edition of Spark only supports a single billable type.');
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'spark');

        $this->configureRoutes();
        $this->configureMigrations();
        $this->configureTranslations();
        $this->configurePublishing();
    }

    /**
     * Configure the routes offered by the application.
     */
    protected function configureRoutes(): void
    {
        Route::group([], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');
        });
    }

    /**
     * Configure Spark migrations.
     */
    protected function configureMigrations(): void
    {
        if (Spark::runsMigrations() && $this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }

    /**
     * Configure Spark translations.
     */
    protected function configureTranslations(): void
    {
        $this->loadJsonTranslationsFrom(lang_path('spark'));
    }

    /**
     * Configure publishing for the package.
     */
    protected function configurePublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/spark.php' => config_path('spark.php'),
        ], 'spark-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/spark'),
        ], 'spark-views');

        $this->publishes([
            __DIR__.'/../stubs/en.json' => lang_path('spark/en.json'),
        ], 'spark-lang');

        $this->publishes([
            __DIR__.'/../stubs/SparkServiceProvider.php' => app_path('Providers/SparkServiceProvider.php'),
        ], 'spark-provider');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'spark-migrations');
    }

    /**
     * Register the Spark Artisan commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
            ]);
        }
    }
}
