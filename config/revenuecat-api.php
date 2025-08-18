<?php

return [
    /*
    |--------------------------------------------------------------------------
    | RevenueCat API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the RevenueCat API wrapper.
    | You can publish this config file using:
    | php artisan vendor:publish --tag=revenuecat-api-config
    |
    */

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | Your RevenueCat API key. You can find this in your RevenueCat dashboard
    | under Project Settings > API Keys.
    |
    */
    'api_key' => env('REVENUECAT_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the RevenueCat API. This should typically be the
    | production URL unless you're testing with a different environment.
    |
    */
    'base_url' => env('REVENUECAT_BASE_URL', 'https://api.revenuecat.com/v2'),

    /*
    |--------------------------------------------------------------------------
    | Project ID (Required)
    |--------------------------------------------------------------------------
    |
    | Your RevenueCat Project ID. Endpoints are scoped to a project and
    | begin with /projects/{project_id}. This is required for API calls.
    |
    */
    'project_id' => env('REVENUECAT_PROJECT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout in seconds for API requests. Default is 30 seconds.
    |
    */
    'timeout' => env('REVENUECAT_TIMEOUT', 30),

];
