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

test('get returns response from client with encoded purchase id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/purchases/test-purchase-id' => Http::response([
            'id' => 'test-purchase-id',
            'product_id' => 'product123',
            'purchase_date' => '2024-01-01T00:00:00Z',
        ], 200),
    ]);

    $purchaseId = 'test-purchase-id';
    $response = RevenueCatClient::purchases()->get($purchaseId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('test-purchase-id');
});

test('listOfEntitlements returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/purchases/test-purchase-id/entitlements' => Http::response([
            'entitlements' => [
                ['id' => 'entitlement1', 'identifier' => 'premium_access'],
                ['id' => 'entitlement2', 'identifier' => 'basic_access'],
            ],
        ], 200),
    ]);

    $purchaseId = 'test-purchase-id';
    $response = RevenueCatClient::purchases()->listOfEntitlements($purchaseId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('entitlements'))->toHaveCount(2);
});

test('get method properly encodes special characters in purchase id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/purchases/test%20purchase%20with%20spaces%20%26%20special%20chars' => Http::response([
            'id' => 'test purchase with spaces & special chars',
            'product_id' => 'special_product',
        ], 200),
    ]);

    $purchaseId = 'test purchase with spaces & special chars';
    $response = RevenueCatClient::purchases()->get($purchaseId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('test purchase with spaces & special chars');
});

test('listOfEntitlements method properly encodes special characters in purchase id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/purchases/test%20purchase%20with%20spaces%20%26%20special%20chars/entitlements' => Http::response([
            'entitlements' => [],
        ], 200),
    ]);

    $purchaseId = 'test purchase with spaces & special chars';
    $response = RevenueCatClient::purchases()->listOfEntitlements($purchaseId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('entitlements'))->toBe([]);
});
