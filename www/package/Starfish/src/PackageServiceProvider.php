<?php

namespace Starfish\Envx;

use Illuminate\Support\ServiceProvider;

/**
 * Class PackageServiceProvider
 *
 * This ServiceProvider is responsible for registering the package's services, publishing configurations, and binding dependencies.
 */
class PackageServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function register()
    {
        // Merge the package configuration file
        $this->mergeConfigFrom(__DIR__ . '/../config/envx.php', 'envx');

        // Register any package-specific bindings here
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Publish configuration file
        $this->publishes([
            __DIR__ . '/../config/envx.php' => config_path('envx.php'),
        ], 'config');

        // Load routes if applicable
//        if (file_exists(__DIR__.'/../routes/web.php')) {
//            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
//        }

        // Load migrations if applicable
//        if (is_dir(__DIR__.'/../database/migrations')) {
//            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
//        }

        // Load views if applicable
//        if (is_dir(__DIR__.'/../resources/views')) {
//            $this->loadViewsFrom(__DIR__.'/../resources/views', 'envx');
//        }

//        if ($this->app->runningInConsole()) {
//            $this->commands([
//                \Starfish\EnvX\Console\CheckEnvXConfig::class,
//            ]);
//        }
    }
}
