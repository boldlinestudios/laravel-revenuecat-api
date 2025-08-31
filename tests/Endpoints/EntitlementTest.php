<?php

use BoldlineStudios\RevenueCatApi\Data\EntitlementData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\ProductData;
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
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

test('list returns ListPage of EntitlementData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements?limit=10' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'entitlement', 'id' => 'ent1', 'lookup_key' => 'premium', 'display_name' => 'Premium'],
                ['object' => 'entitlement', 'id' => 'ent2', 'lookup_key' => 'pro', 'display_name' => 'Pro'],
            ],
            'next_page' => null,
            'url' => '/v2/projects/test_project/entitlements',
        ], 200),
    ]);

    $response = RevenueCat::entitlements()->list(10);

    expect($response)->toBeInstanceOf(ListPage::class);
    expect(count($response->items()))->toBe(2);
});

test('create returns EntitlementData DTO', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements' => Http::response([
            'object' => 'entitlement',
            'id' => 'new_entitlement_id',
            'lookup_key' => 'new_premium',
            'display_name' => 'New Premium',
            'created_at' => 1704067200000,
        ], 201),
    ]);

    $entitlement = RevenueCat::entitlements()->create('premium', 'New Premium');

    expect($entitlement)->toBeInstanceOf(EntitlementData::class);
    expect($entitlement->getId())->toBe('new_entitlement_id');
    expect($entitlement->getLookupKey())->toBe('new_premium');
    expect($entitlement->getDisplayName())->toBe('New Premium');
});

test('get returns EntitlementData with encoded entitlement id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements/test-entitlement-id' => Http::response([
            'object' => 'entitlement',
            'id' => 'test-entitlement-id',
            'lookup_key' => 'premium',
            'display_name' => 'Premium',
        ], 200),
    ]);

    $entitlementId = 'test-entitlement-id';
    $entitlement = RevenueCat::entitlements()->get($entitlementId);

    expect($entitlement)->toBeInstanceOf(EntitlementData::class);
    expect($entitlement->getId())->toBe('test-entitlement-id');
});

test('update returns EntitlementData DTO', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements/test-entitlement-id' => Http::response([
            'object' => 'entitlement',
            'id' => 'test-entitlement-id',
            'lookup_key' => 'premium_plus',
            'display_name' => 'Updated Premium',
            'created_at' => 1704067200000,
        ], 200),
    ]);

    $entitlementId = 'test-entitlement-id';
    $entitlement = RevenueCat::entitlements()->update($entitlementId, 'Updated Premium');

    expect($entitlement)->toBeInstanceOf(EntitlementData::class);
    expect($entitlement->getDisplayName())->toBe('Updated Premium');
    expect($entitlement->getLookupKey())->toBe('premium_plus');
    expect($entitlement->getCreatedAtMs())->toBe(1704067200000);
});

test('delete returns true when deletion succeeds', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements/test-entitlement-id' => Http::response([
            'object' => 'entitlement',
            'id' => 'test-entitlement-id',
            'deleted_at' => 1658399423658,
        ], 200),
    ]);

    $entitlementId = 'test-entitlement-id';
    $deleted = RevenueCat::entitlements()->delete($entitlementId);

    expect($deleted)->toBeTrue();
});

test('listOfProducts returns ListPage of ProductData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements/test-entitlement-id/products' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'product', 'id' => 'prod1', 'store_identifier' => 'sku_monthly', 'type' => 'subscription'],
                ['object' => 'product', 'id' => 'prod2', 'store_identifier' => 'sku_yearly', 'type' => 'subscription'],
            ],
            'next_page' => null,
            'url' => '/v2/projects/test_project/entitlements/test-entitlement-id/products',
        ], 200),
    ]);

    $entitlementId = 'test-entitlement-id';
    $response = RevenueCat::entitlements()->listOfProducts($entitlementId);

    expect($response)->toBeInstanceOf(ListPage::class);
    expect(count($response->items()))->toBe(2);
    expect($response->items()[0])->toBeInstanceOf(ProductData::class);
    expect($response->nextCursor())->toBeNull();
});

test('get method properly encodes special characters in entitlement id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements/test%20entitlement%20with%20spaces%20%26%20special%20chars' => Http::response([
            'object' => 'entitlement',
            'id' => 'test entitlement with spaces & special chars',
            'lookup_key' => 'special_entitlement',
            'display_name' => 'Special Entitlement',
        ], 200),
    ]);

    $entitlementId = 'test entitlement with spaces & special chars';
    $entitlement = RevenueCat::entitlements()->get($entitlementId);

    expect($entitlement)->toBeInstanceOf(EntitlementData::class);
    expect($entitlement->getId())->toBe('test entitlement with spaces & special chars');
});

test('list method works with empty query array', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements' => Http::response([
            'object' => 'list',
            'items' => [],
            'next_page' => null,
            'url' => '/v2/projects/test_project/entitlements',
        ], 200),
    ]);

    $response = RevenueCat::entitlements()->list();

    expect($response)->toBeInstanceOf(ListPage::class);
    expect(count($response->items()))->toBe(0);
});

test('attachProducts attaches products to entitlement and returns updated EntitlementData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements/test-entitlement-id/attach_products' => Http::response([
            'object' => 'entitlement',
            'project_id' => 'proj1ab2c3d4',
            'id' => 'entla1b2c3d4e5',
            'lookup_key' => 'premium',
            'display_name' => 'Premium',
            'created_at' => 1658399423658,
            'products' => [
                'object' => 'list',
                'items' => [
                    [
                        'object' => 'product',
                        'id' => 'prod1a2b3c4d5e',
                        'store_identifier' => 'rc_1w_199',
                        'type' => 'subscription',
                        'subscription' => [
                            'duration' => 'P1M',
                            'grace_period_duration' => 'P3D',
                            'trial_duration' => 'P1W',
                        ],
                        'one_time' => [
                            'is_consumable' => true,
                        ],
                        'created_at' => 1658399423658,
                        'app_id' => 'app1a2b3c4',
                        'app' => [
                            'object' => 'app',
                            'id' => 'app1a2b3c4',
                            'name' => 'string',
                            'created_at' => 1658399423658,
                            'type' => 'app_store',
                        ],
                        'display_name' => 'Premium Monthly 2023',
                    ],
                ],
                'next_page' => '/v2/projects/proj1ab2c3d4/entitlements/entle1a2b3c4d5/products?starting_after=prodeab21dac',
                'url' => '/v2/projects/proj1ab2c3d4/entitlements/entle1a2b3c4d5/products',
            ],
        ], 200),
    ]);

    $result = RevenueCat::entitlements()->attachProducts('test-entitlement-id', ['prod1a2b3c4d5e']);

    expect($result)->toBeInstanceOf(EntitlementData::class);
    expect($result->getId())->toBe('entla1b2c3d4e5');
    expect($result->getLookupKey())->toBe('premium');
    expect($result->getProducts())->toBeArray();
    expect($result->getProducts())->not()->toBeNull();
    expect($result->getProducts())->toHaveCount(1);
    expect($result->getProducts()[0])->toBeInstanceOf(ProductData::class);
    expect($result->getProducts()[0]->getId())->toBe('prod1a2b3c4d5e');
});

test('detachProducts detaches products from entitlement and returns updated EntitlementData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements/test-entitlement-id/detach_products' => Http::response([
            'object' => 'entitlement',
            'project_id' => 'proj1ab2c3d4',
            'id' => 'entla1b2c3d4e5',
            'lookup_key' => 'premium',
            'display_name' => 'Premium',
            'created_at' => 1658399423658,
            'products' => [
                'object' => 'list',
                'items' => [],
                'next_page' => null,
                'url' => '/v2/projects/proj1ab2c3d4/entitlements/entle1a2b3c4d5/products',
            ],
        ], 200),
    ]);

    $result = RevenueCat::entitlements()->detachProducts('test-entitlement-id', ['prod1a2b3c4d5e']);

    expect($result)->toBeInstanceOf(EntitlementData::class);
    expect($result->getId())->toBe('entla1b2c3d4e5');
    expect($result->getProducts())->toBeArray();
    expect($result->getProducts())->toHaveCount(0);
});
