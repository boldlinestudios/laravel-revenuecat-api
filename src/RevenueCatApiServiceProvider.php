<?php

namespace BoldlineStudios\RevenueCatApi;

use Illuminate\Support\ServiceProvider;
use BoldlineStudios\RevenueCatApi\Services\RevenueCatApiService;

class RevenueCatApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/revenuecat-api.php', 'revenuecat-api'
        );

        $this->app->singleton(RevenueCatApiService::class, function ($app) {
            return new RevenueCatApiService(
                config('revenuecat-api.api_key'),
                config('revenuecat-api.base_url'),
                config('revenuecat-api.timeout')
            );
        });

        $this->app->alias(RevenueCatApiService::class, 'revenuecat-api');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/revenuecat-api.php' => $this->app->configPath('revenuecat-api.php'),
            ], 'revenuecat-api-config');
        }
    }
}
