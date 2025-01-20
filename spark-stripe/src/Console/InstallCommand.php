<?php

namespace Spark\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spark:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Spark resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->callSilent('vendor:publish', ['--tag' => 'spark-provider']);
        $this->callSilent('vendor:publish', ['--tag' => 'spark-config']);
        $this->callSilent('vendor:publish', ['--tag' => 'spark-views']);
        $this->callSilent('vendor:publish', ['--tag' => 'spark-lang']);

        $this->registerSparkServiceProvider();

        $this->info('Spark scaffolding installed successfully.');
    }

    /**
     * Register the Spark service provider in the application configuration file.
     *
     * @return void
     */
    protected function registerSparkServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace.'\\Providers\\SparkServiceProvider::class')) {
            return;
        }

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\EventServiceProvider::class,",
            "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL."        {$namespace}\Providers\SparkServiceProvider::class,",
            $appConfig
        ));

        file_put_contents(app_path('Providers/SparkServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/SparkServiceProvider.php'))
        ));
    }
}
