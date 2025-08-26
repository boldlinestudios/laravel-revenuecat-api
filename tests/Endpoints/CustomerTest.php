<?php

use BoldlineStudios\RevenueCatApi\Data\CustomerData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
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

test('list returns ListPage of CustomerData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers?limit=10' => Http::response([
            'object' => 'list',
            'items' => [
                ['id' => 'customer1'],
                ['id' => 'customer2'],
            ],
            'next_page' => null,
            'url' => '/v2/projects/test_project/customers',
        ], 200),
    ]);

    $list = RevenueCat::customers()->list(10);

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
});

test('create returns CustomerData DTO', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers' => Http::response([
            'object' => 'customer',
            'id' => 'new_customer_id',
        ], 201),
    ]);

    $customer = RevenueCat::customers()->create('new_customer_id', [
        ['name' => '$email', 'value' => 'test@example.com'],
    ]);

    expect($customer)->toBeInstanceOf(CustomerData::class);
    expect($customer->getId())->toBe('new_customer_id');
});

test('get returns CustomerData with encoded customer id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id' => Http::response([
            'object' => 'customer',
            'id' => 'test-customer-id',
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $customer = RevenueCat::customers()->get($customerId);

    expect($customer)->toBeInstanceOf(CustomerData::class);
    expect($customer->getId())->toBe('test-customer-id');
});

test('delete returns true when deletion succeeds', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id' => Http::response([
            'object' => 'customer',
            'id' => 'test-customer-id',
            'deleted_at' => 1658399423658,
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $deleted = RevenueCat::customers()->delete($customerId);

    expect($deleted)->toBeTrue();
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
    $response = RevenueCat::customers()->listOfSubscriptions($customerId);

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
    $response = RevenueCat::customers()->listOfPurchases($customerId);

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
    $response = RevenueCat::customers()->listOfActiveEntitlements($customerId);

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
    $response = RevenueCat::customers()->listOfAliases($customerId);

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
    $response = RevenueCat::customers()->listOfVirtualCurrencyBalances($customerId);

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
    $response = RevenueCat::customers()->listOfAttributes($customerId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('attributes'))->toHaveCount(2);
});

test('get method properly encodes special characters in customer id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test%20customer%20with%20spaces%20%26%20special%20chars' => Http::response([
            'object' => 'customer',
            'id' => 'test customer with spaces & special chars',
        ], 200),
    ]);

    $customerId = 'test customer with spaces & special chars';
    $customer = RevenueCat::customers()->get($customerId);

    expect($customer)->toBeInstanceOf(CustomerData::class);
    expect($customer->getId())->toBe('test customer with spaces & special chars');
});

test('list method works with empty query array', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers' => Http::response([
            'object' => 'list',
            'items' => [],
            'next_page' => null,
            'url' => '/v2/projects/test_project/customers',
        ], 200),
    ]);

    $list = RevenueCat::customers()->list();

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(0);
});

test('create throws if attributes is not a list', function () {
    expect(fn () => RevenueCat::customers()->create('bad_id', [
        'name' => '$email',
        'value' => 'test@example.com',
    ]))->toThrow(\InvalidArgumentException::class, 'Attributes must be a list of {name, value} items.');
});
