<?php

use Orchestra\Testbench\TestCase;

uses(TestCase::class)
    ->beforeEach(function () {
        // Register the package's service provider
        $this->app->register(\BoldlineStudios\RevenueCatApi\RevenueCatApiServiceProvider::class);

        // Set up test configuration
        config([
            'revenuecat-api.api_key' => 'test_api_key',
            'revenuecat-api.base_url' => 'https://api.example.com/v2',
            'revenuecat-api.project_id' => 'test_project',
            'revenuecat-api.timeout' => 30,
        ]);
    })
    ->in(__DIR__);
