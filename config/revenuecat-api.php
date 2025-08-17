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
    'base_url' => env('REVENUECAT_BASE_URL', 'https://api.revenuecat.com'),

    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout in seconds for API requests. Default is 30 seconds.
    |
    */
    'timeout' => env('REVENUECAT_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Retry Attempts
    |--------------------------------------------------------------------------
    |
    | Number of retry attempts for failed requests. Default is 3.
    |
    */
    'retry_attempts' => env('REVENUECAT_RETRY_ATTEMPTS', 3),

    /*
    |--------------------------------------------------------------------------
    | Retry Delay
    |--------------------------------------------------------------------------
    |
    | Delay in seconds between retry attempts. Default is 1 second.
    |
    */
    'retry_delay' => env('REVENUECAT_RETRY_DELAY', 1),
];
