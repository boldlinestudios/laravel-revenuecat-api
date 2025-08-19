<?php

use BoldlineStudios\RevenueCatApi\Facades\RevenueCatClient;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    // Override config for example domain to avoid hitting real endpoints
    config([
        'revenuecat-api.api_key' => 'test_api_key',
        'revenuecat-api.base_url' => 'https://api.example.com/v2',
        'revenuecat-api.project_id' => 'test_project',
        'revenuecat-api.timeout' => 30,
    ]);
});

test('list returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements?limit=10' => Http::response([
            'entitlements' => [
                ['id' => 'ent1', 'identifier' => 'premium'],
                ['id' => 'ent2', 'identifier' => 'pro'],
            ],
        ], 200),
    ]);

    $query = ['limit' => 10];
    $response = RevenueCatClient::entitlements()->list($query);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('entitlements'))->toHaveCount(2);
});

test('create returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements' => Http::response([
            'id' => 'new_entitlement_id',
            'identifier' => 'new_premium',
            'created_at' => '2024-01-01T00:00:00Z',
        ], 201),
    ]);

    $data = ['identifier' => 'new_premium', 'type' => 'subscription'];
    $response = RevenueCatClient::entitlements()->create($data);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('new_entitlement_id');
});

test('get returns response from client with encoded entitlement id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements/test-entitlement-id' => Http::response([
            'id' => 'test-entitlement-id',
            'identifier' => 'premium',
            'type' => 'subscription',
        ], 200),
    ]);

    $entitlementId = 'test-entitlement-id';
    $response = RevenueCatClient::entitlements()->get($entitlementId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('test-entitlement-id');
});

test('update returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements/test-entitlement-id' => Http::response([
            'id' => 'test-entitlement-id',
            'identifier' => 'premium_plus',
            'type' => 'subscription',
        ], 200),
    ]);

    $entitlementId = 'test-entitlement-id';
    $data = ['identifier' => 'premium_plus'];
    $response = RevenueCatClient::entitlements()->update($entitlementId, $data);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('identifier'))->toBe('premium_plus');
});

test('delete returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements/test-entitlement-id' => Http::response([], 204),
    ]);

    $entitlementId = 'test-entitlement-id';
    $response = RevenueCatClient::entitlements()->delete($entitlementId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->status())->toBe(204);
});

test('listOfProducts returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements/test-entitlement-id/products' => Http::response([
            'products' => [
                ['id' => 'prod1', 'identifier' => 'premium_monthly'],
                ['id' => 'prod2', 'identifier' => 'premium_yearly'],
            ],
        ], 200),
    ]);

    $entitlementId = 'test-entitlement-id';
    $response = RevenueCatClient::entitlements()->listOfProducts($entitlementId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('products'))->toHaveCount(2);
});

test('get method properly encodes special characters in entitlement id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements/test%20entitlement%20with%20spaces%20%26%20special%20chars' => Http::response([
            'id' => 'test entitlement with spaces & special chars',
            'identifier' => 'special_entitlement',
        ], 200),
    ]);

    $entitlementId = 'test entitlement with spaces & special chars';
    $response = RevenueCatClient::entitlements()->get($entitlementId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('test entitlement with spaces & special chars');
});

test('list method works with empty query array', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements' => Http::response([
            'entitlements' => [],
        ], 200),
    ]);

    $response = RevenueCatClient::entitlements()->list();

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('entitlements'))->toBe([]);
});
