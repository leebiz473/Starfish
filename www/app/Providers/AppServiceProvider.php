<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Barryvdh\Debugbar\ServiceProvider as DebugbarServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (false === $this->app->environment('production')) {
            $this->app->register(IdeHelperServiceProvider::class);
            $this->app->register(DebugbarServiceProvider::class);

        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
