<?php

namespace BoldlineStudios\RevenueCatApi;

use BoldlineStudios\RevenueCatApi\Services\RevenueCatApiService;
use Illuminate\Support\ServiceProvider;

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

            $apiKey = config('revenuecat-api.api_key');
            $baseUrl = config('revenuecat-api.base_url');
            $timeout = config('revenuecat-api.timeout');

            if (!is_string($apiKey) || empty($apiKey)) {
                throw new \InvalidArgumentException('REVENUECAT_API_KEY must be a string and not empty');
            }

            if (!is_string($baseUrl) || empty($baseUrl)) {
                throw new \InvalidArgumentException('REVENUECAT_BASE_URL must be a string and not empty');
            }

            if (!is_int($timeout) || empty($timeout)) {
                throw new \InvalidArgumentException('REVENUECAT_TIMEOUT must be an integer and not empty');
            }

            return new RevenueCatApiService(
                $apiKey,
                $baseUrl,
                $timeout
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
