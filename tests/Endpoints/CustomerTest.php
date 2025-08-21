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
        'https://api.example.com/v2/projects/test_project/customers?limit=10' => Http::response([
            'customers' => [
                ['id' => 'customer1', 'app_user_id' => 'user1'],
                ['id' => 'customer2', 'app_user_id' => 'user2'],
            ],
        ], 200),
    ]);

    $response = RevenueCatClient::customers()->list(10);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('customers'))->toHaveCount(2);
});

test('create returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers' => Http::response([
            'id' => 'new_customer_id',
            'app_user_id' => 'new_user',
            'created_at' => '2024-01-01T00:00:00Z',
        ], 201),
    ]);

    $data = ['app_user_id' => 'new_user', 'email' => 'test@example.com'];
    $response = RevenueCatClient::customers()->create($data);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('new_customer_id');
});

test('get returns response from client with encoded customer id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id' => Http::response([
            'id' => 'test-customer-id',
            'app_user_id' => 'test_user',
            'email' => 'test@example.com',
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $response = RevenueCatClient::customers()->get($customerId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('test-customer-id');
});

test('delete returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id' => Http::response([], 204),
    ]);

    $customerId = 'test-customer-id';
    $response = RevenueCatClient::customers()->delete($customerId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->status())->toBe(204);
});

test('listOfSubscriptions returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/subscriptions' => Http::response([
            'subscriptions' => [
                ['id' => 'sub1', 'product_id' => 'prod1'],
                ['id' => 'sub2', 'product_id' => 'prod2'],
            ],
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $response = RevenueCatClient::customers()->listOfSubscriptions($customerId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('subscriptions'))->toHaveCount(2);
});

test('listOfPurchases returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/purchases' => Http::response([
            'purchases' => [
                ['id' => 'purchase1', 'product_id' => 'prod1'],
                ['id' => 'purchase2', 'product_id' => 'prod2'],
            ],
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $response = RevenueCatClient::customers()->listOfPurchases($customerId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('purchases'))->toHaveCount(2);
});

test('listOfActiveEntitlements returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/active_entitlements' => Http::response([
            'entitlements' => [
                ['id' => 'ent1', 'identifier' => 'premium'],
                ['id' => 'ent2', 'identifier' => 'pro'],
            ],
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $response = RevenueCatClient::customers()->listOfActiveEntitlements($customerId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('entitlements'))->toHaveCount(2);
});

test('listOfAliases returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/aliases' => Http::response([
            'aliases' => [
                ['id' => 'alias1', 'alias' => 'user1@example.com'],
                ['id' => 'alias2', 'alias' => 'user1_alt'],
            ],
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $response = RevenueCatClient::customers()->listOfAliases($customerId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('aliases'))->toHaveCount(2);
});

test('listOfVirtualCurrencyBalances returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/virtual_currencies' => Http::response([
            'virtual_currencies' => [
                ['id' => 'vc1', 'currency' => 'coins', 'balance' => 100],
                ['id' => 'vc2', 'currency' => 'gems', 'balance' => 50],
            ],
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $response = RevenueCatClient::customers()->listOfVirtualCurrencyBalances($customerId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('virtual_currencies'))->toHaveCount(2);
});

test('listOfAttributes returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/attributes' => Http::response([
            'attributes' => [
                ['key' => 'country', 'value' => 'US'],
                ['key' => 'language', 'value' => 'en'],
            ],
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $response = RevenueCatClient::customers()->listOfAttributes($customerId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('attributes'))->toHaveCount(2);
});

test('get method properly encodes special characters in customer id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test%20customer%20with%20spaces%20%26%20special%20chars' => Http::response([
            'id' => 'test customer with spaces & special chars',
            'app_user_id' => 'special_user',
        ], 200),
    ]);

    $customerId = 'test customer with spaces & special chars';
    $response = RevenueCatClient::customers()->get($customerId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('test customer with spaces & special chars');
});

test('list method works with empty query array', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers' => Http::response([
            'customers' => [],
        ], 200),
    ]);

    $response = RevenueCatClient::customers()->list();

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('customers'))->toBe([]);
});
