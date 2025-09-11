<?php

use BoldLineStudios\RevenueCatApi\Data\EntitlementData;
use BoldLineStudios\RevenueCatApi\Data\ListPage;
use BoldLineStudios\RevenueCatApi\Data\ProductData;
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

test('getEntitlement calls entitlements()->get() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements/test-entitlement-id' => Http::response([
            'object' => 'entitlement',
            'id' => 'test-entitlement-id',
        ], 200),
    ]);

    $entitlement = RevenueCat::getEntitlement('test-entitlement-id');

    expect($entitlement)->toBeInstanceOf(EntitlementData::class);
    expect($entitlement->getId())->toBe('test-entitlement-id');
});

test('listEntitlements calls entitlements()->all() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements?limit=10' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'entitlement', 'id' => 'ent1'],
                ['object' => 'entitlement', 'id' => 'ent2'],
            ],
        ], 200),
    ]);

    $list = RevenueCat::listEntitlements(10);

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
});

test('createEntitlement calls entitlements()->create() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements' => Http::response([
            'object' => 'entitlement',
            'project_id' => 'proj1ab2c3d4',
            'id' => 'entla1b2c3d4e5',
            'lookup_key' => 'premium',
            'display_name' => 'Premium',
            'created_at' => 1658399423658,
        ], 201),
    ]);

    $entitlement = RevenueCat::createEntitlement('premium', 'Premium');

    expect($entitlement)->toBeInstanceOf(EntitlementData::class);
    expect($entitlement->getId())->toBe('entla1b2c3d4e5');
    expect($entitlement->getLookupKey())->toBe('premium');
    expect($entitlement->getDisplayName())->toBe('Premium');
});

test('updateEntitlement calls entitlements()->update() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements/test-entitlement-id' => Http::response([
            'object' => 'entitlement',
            'id' => 'test-entitlement-id',
            'lookup_key' => 'premium_plus',
            'project_id' => 'proj1ab2c3d4',
            'display_name' => 'Updated',
            'created_at' => 1704067200000,
        ], 200),
    ]);

    $entitlement = RevenueCat::updateEntitlement('test-entitlement-id', 'Updated');

    expect($entitlement)->toBeInstanceOf(EntitlementData::class);
    expect($entitlement->getDisplayName())->toBe('Updated');
    expect($entitlement->getLookupKey())->toBe('premium_plus');
    expect($entitlement->getCreatedAtMs())->toBe(1704067200000);
});

test('deleteEntitlement calls entitlements()->delete() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements/test-entitlement-id' => Http::response([
            'object' => 'entitlement',
            'id' => 'test-entitlement-id',
            'deleted_at' => 1658399423658,
        ], 200),
    ]);

    $deleted = RevenueCat::deleteEntitlement('test-entitlement-id');
    expect($deleted)->toBeTrue();
});

test('listEntitlementProducts calls entitlements()->listOfProducts() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/entitlements/test-entitlement-id/products' => Http::response([
            'object' => 'list',
            'items' => [
                [
                    'object' => 'product',
                    'id' => 'prod1',
                    'store_identifier' => 'sku1',
                    'type' => 'subscription', 'created_at' => 1658399423658,
                    'app_id' => 'test_app_id',
                    'app' => [
                        'object' => 'app',
                        'id' => 'test_app_id',
                        'name' => 'Test app',
                        'created_at' => 1658399423658,
                        'type' => 'app_store',
                    ],
                    'display_name' => 'Product 1',
                ],
                [
                    'object' => 'product',
                    'id' => 'prod2',
                    'store_identifier' => 'sku2',
                    'type' => 'one_time',
                    'created_at' => 1658399423658,
                    'app_id' => 'test_app_id',
                    'app' => [
                        'object' => 'app',
                        'id' => 'test_app_id',
                        'name' => 'Test app',
                        'created_at' => 1658399423658,
                        'type' => 'app_store',
                    ],
                    'display_name' => 'Product 2',
                ],
            ],
        ], 200),
    ]);

    $response = RevenueCat::listEntitlementProducts('test-entitlement-id');

    expect($response)->toBeInstanceOf(ListPage::class);
    expect(count($response->items()))->toBe(2);
    expect($response->items()[0])->toBeInstanceOf(ProductData::class);
});

test('attachEntitlementProducts calls entitlements()->attachProducts() with correct parameters', function () {
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
                    ],
                ],
            ],
        ], 200),
    ]);

    $entitlement = RevenueCat::attachEntitlementProducts('test-entitlement-id', ['prod1a2b3c4d5e']);

    expect($entitlement)->toBeInstanceOf(EntitlementData::class);
    expect($entitlement->getProducts())->toBeArray();
});

test('detachEntitlementProducts calls entitlements()->detachProducts() with correct parameters', function () {
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
            ],
        ], 200),
    ]);

    $entitlement = RevenueCat::detachEntitlementProducts('test-entitlement-id', ['prod1a2b3c4d5e']);

    expect($entitlement)->toBeInstanceOf(EntitlementData::class);
    expect($entitlement->getProducts())->toBeArray();
});
