<?php

use BoldlineStudios\RevenueCatApi\Facades\RevenueCatApi;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

test('it can get subscriber', function () {
    Http::fake([
        'https://api.revenuecat.com/v2/subscribers/user123' => Http::response([
            'original_app_user_id' => 'user123',
            'original_application_id' => 'app123',
            'first_seen' => '2024-01-01T00:00:00Z',
            'last_seen' => '2024-01-01T00:00:00Z',
        ], 200),
    ]);

    $response = RevenueCatApi::getSubscriber('user123');

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('original_app_user_id'))->toBe('user123');
});

test('it can get subscriber entitlements', function () {
    Http::fake([
        'https://api.revenuecat.com/v2/subscribers/user123/entitlements' => Http::response([
            'entitlements' => [
                'premium' => [
                    'identifier' => 'premium',
                    'is_active' => true,
                    'expires_date' => '2024-12-31T23:59:59Z',
                    'product_identifier' => 'premium_monthly',
                    'purchase_date' => '2024-01-01T00:00:00Z',
                ],
            ],
        ], 200),
    ]);

    $response = RevenueCatApi::getSubscriberEntitlements('user123');

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('entitlements.premium.is_active'))->toBeTrue();
});

test('it can grant promotional entitlement', function () {
    Http::fake([
        'https://api.revenuecat.com/v2/subscribers/user123/entitlements' => Http::response([
            'entitlement_identifier' => 'premium',
            'duration' => 'month',
            'start_time' => '2024-01-01T00:00:00Z',
        ], 200),
    ]);

    $data = [
        'entitlement_identifier' => 'premium',
        'duration' => 'month',
        'start_time' => '2024-01-01T00:00:00Z',
    ];

    $response = RevenueCatApi::grantPromotionalEntitlement('user123', $data);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
});

test('it can get products', function () {
    Http::fake([
        'https://api.revenuecat.com/v2/products' => Http::response([
            'products' => [
                [
                    'identifier' => 'premium_monthly',
                    'type' => 'subscription',
                ],
            ],
        ], 200),
    ]);

    $response = RevenueCatApi::getProducts();

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('products.0.identifier'))->toBe('premium_monthly');
});

test('it can get offerings', function () {
    Http::fake([
        'https://api.revenuecat.com/v2/offerings' => Http::response([
            'offerings' => [
                [
                    'identifier' => 'default',
                    'description' => 'Default offering',
                ],
            ],
        ], 200),
    ]);

    $response = RevenueCatApi::getOfferings();

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('offerings.0.identifier'))->toBe('default');
});
