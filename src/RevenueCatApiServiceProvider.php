<?php

namespace BoldlineStudios\RevenueCatApi;

use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
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

        $this->app->singleton(RevenueCatClient::class, function ($app) {
            $apiKey = config('revenuecat-api.api_key');
            $projectId = config('revenuecat-api.project_id');
            $baseUrl = config('revenuecat-api.base_url');
            $timeout = config('revenuecat-api.timeout');

            if (! is_string($apiKey) || $apiKey === '') {
                throw new \InvalidArgumentException('REVENUECAT_API_KEY must be a non-empty string');
            }
            if (! is_string($projectId) || $projectId === '') {
                throw new \InvalidArgumentException('REVENUECAT_PROJECT_ID must be a non-empty string');
            }
            if (! is_string($baseUrl) || $baseUrl === '') {
                throw new \InvalidArgumentException('REVENUECAT_BASE_URL must be a non-empty string');
            }
            if (! is_int($timeout) || $timeout <= 0) {
                throw new \InvalidArgumentException('REVENUECAT_TIMEOUT must be a positive integer');
            }

            return new RevenueCatClient(
                $apiKey, $baseUrl, $projectId, $timeout
            );
        });

        $this->app->singleton('revenuecat', function ($app) {
            return new RevenueCat($app->make(RevenueCatClient::class));
        });
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
