<?php

namespace App\Providers;

use App\Services\WeatherService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(WeatherService::class, function ($app) {
            Log::info('WeatherService singleton is being created.');
            return new WeatherService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
