<?php

use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
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

test('get returns response from client with encoded subscription id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id' => Http::response([
            'id' => 'test-subscription-id',
            'product_id' => 'product123',
            'status' => 'active',
            'expires_date' => '2024-12-31T23:59:59Z',
        ], 200),
    ]);

    $subscriptionId = 'test-subscription-id';
    $response = RevenueCat::subscriptions()->get($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('test-subscription-id');
});

test('listOfEntitlements returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/entitlements' => Http::response([
            'entitlements' => [
                ['id' => 'entitlement1', 'identifier' => 'premium_access'],
                ['id' => 'entitlement2', 'identifier' => 'basic_access'],
            ],
        ], 200),
    ]);

    $subscriptionId = 'test-subscription-id';
    $response = RevenueCat::subscriptions()->listOfEntitlements($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('entitlements'))->toHaveCount(2);
});

test('listOfTransactions returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/transactions' => Http::response([
            'transactions' => [
                ['id' => 'transaction1', 'amount' => 9.99, 'currency' => 'USD'],
                ['id' => 'transaction2', 'amount' => 9.99, 'currency' => 'USD'],
            ],
        ], 200),
    ]);

    $subscriptionId = 'test-subscription-id';
    $response = RevenueCat::subscriptions()->listOfTransactions($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('transactions'))->toHaveCount(2);
});

test('getCustomerPortalUrl returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/authenticated_management_url' => Http::response([
            'url' => 'https://portal.example.com/access/abc123',
            'expires_at' => '2024-01-01T01:00:00Z',
        ], 200),
    ]);

    $subscriptionId = 'test-subscription-id';
    $response = RevenueCat::subscriptions()->getCustomerPortalUrl($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('url'))->toBe('https://portal.example.com/access/abc123');
});

test('cancelWebBillingSubscription returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/actions/cancel' => Http::response([
            'id' => 'test-subscription-id',
            'status' => 'cancelled',
            'cancelled_at' => '2024-01-01T00:00:00Z',
        ], 200),
    ]);

    $subscriptionId = 'test-subscription-id';
    $response = RevenueCat::subscriptions()->cancelWebBillingSubscription($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('status'))->toBe('cancelled');
});

test('refundWebBillingSubscription returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/actions/refund' => Http::response([
            'id' => 'test-subscription-id',
            'status' => 'refunded',
            'refunded_at' => '2024-01-01T00:00:00Z',
        ], 200),
    ]);

    $subscriptionId = 'test-subscription-id';
    $response = RevenueCat::subscriptions()->refundWebBillingSubscription($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('status'))->toBe('refunded');
});

test('get method properly encodes special characters in subscription id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars' => Http::response([
            'id' => 'test subscription with spaces & special chars',
            'product_id' => 'special_product',
        ], 200),
    ]);

    $subscriptionId = 'test subscription with spaces & special chars';
    $response = RevenueCat::subscriptions()->get($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('test subscription with spaces & special chars');
});

test('listOfEntitlements method properly encodes special characters in subscription id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars/entitlements' => Http::response([
            'entitlements' => [],
        ], 200),
    ]);

    $subscriptionId = 'test subscription with spaces & special chars';
    $response = RevenueCat::subscriptions()->listOfEntitlements($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('entitlements'))->toBe([]);
});

test('listOfTransactions method properly encodes special characters in subscription id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars/transactions' => Http::response([
            'transactions' => [],
        ], 200),
    ]);

    $subscriptionId = 'test subscription with spaces & special chars';
    $response = RevenueCat::subscriptions()->listOfTransactions($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('transactions'))->toBe([]);
});

test('getCustomerPortalUrl method properly encodes special characters in subscription id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars/authenticated_management_url' => Http::response([
            'url' => 'https://portal.example.com/access/def456',
        ], 200),
    ]);

    $subscriptionId = 'test subscription with spaces & special chars';
    $response = RevenueCat::subscriptions()->getCustomerPortalUrl($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('url'))->toBe('https://portal.example.com/access/def456');
});

test('cancelWebBillingSubscription method properly encodes special characters in subscription id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars/actions/cancel' => Http::response([
            'id' => 'test subscription with spaces & special chars',
            'status' => 'cancelled',
        ], 200),
    ]);

    $subscriptionId = 'test subscription with spaces & special chars';
    $response = RevenueCat::subscriptions()->cancelWebBillingSubscription($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('status'))->toBe('cancelled');
});

test('refundWebBillingSubscription method properly encodes special characters in subscription id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars/actions/refund' => Http::response([
            'id' => 'test subscription with spaces & special chars',
            'status' => 'refunded',
        ], 200),
    ]);

    $subscriptionId = 'test subscription with spaces & special chars';
    $response = RevenueCat::subscriptions()->refundWebBillingSubscription($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('status'))->toBe('refunded');
});
