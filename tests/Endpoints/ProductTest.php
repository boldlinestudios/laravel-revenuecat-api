<?php

use BoldLineStudios\RevenueCatApi\Data\ListPage;
use BoldLineStudios\RevenueCatApi\Data\ProductData;
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

test('list returns ListPage of ProductData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products?limit=10' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'product', 'id' => 'product1', 'store_identifier' => 'rc_1w_199'],
                ['object' => 'product', 'id' => 'product2', 'store_identifier' => 'rc_1w_100'],
            ],
            'next_page' => null,
            'url' => '/v2/projects/test_project/products',
        ], 200),
    ]);

    $products = RevenueCat::products()->all(10);

    expect($products)->toBeInstanceOf(ListPage::class);
    expect(count($products->items()))->toBe(2);
    expect($products->items()[0])->toBeInstanceOf(ProductData::class);
    expect($products->items()[0]->getId())->toBe('product1');
    expect($products->items()[1])->toBeInstanceOf(ProductData::class);
    expect($products->items()[1]->getId())->toBe('product2');
});

test('create returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products' => Http::response([
            'object' => 'product',
            'id' => 'new_product_id',
            'store_identifier' => 'rc_1w_199"',
            'type' => 'subscription',
            'created_at' => 1658399423658,
            'subscription' => [
                'duration' => 'P1W',
                'grace_period_duration' => 'P1D',
                'trial_duration' => 'P1D',
            ],
            'one_time' => [
                'is_consumable' => false,
            ],
            'app_id' => 'test_app_id',
            'app' => [
                'object' => 'app',
                'id' => 'test_app_id',
                'name' => 'Test app',
                'created_at' => 1658399423658,
                'type' => 'app_store',
                'app_store' => [
                    'bundle_id' => 'com.example.app',
                ],
            ],
            'display_name' => 'New product',
        ], 201),
    ]);

    $product = RevenueCat::products()->create('new_product', 'New product', 'subscription');

    expect($product)->toBeInstanceOf(ProductData::class);
    expect($product->getId())->toBe('new_product_id');
    expect($product->getType())->toBe('subscription');
    expect($product->getCreatedAtMs())->toBe(1658399423658);
    expect($product->getSubscription()['duration'])->toBe('P1W');
    expect($product->getSubscription()['grace_period_duration'])->toBe('P1D');
    expect($product->getSubscription()['trial_duration'])->toBe('P1D');
    expect($product->getOneTime()['is_consumable'])->toBeFalse();
    expect($product->getApp()->getId())->toBe('test_app_id');
    expect($product->getApp()->getName())->toBe('Test app');
    expect($product->getApp()->getCreatedAtMs())->toBe(1658399423658);
    expect($product->getApp()->getType())->toBe('app_store');
    expect($product->getApp()->getAppStore()['bundle_id'])->toBe('com.example.app');
    expect($product->getDisplayName())->toBe('New product');
});

test('get returns response from client with encoded product id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products/test-product-id' => Http::response([
            'object' => 'product',
            'id' => 'test-product-id',
            'identifier' => 'premium_product',
            'description' => 'Premium product',
        ], 200),
    ]);

    $productId = 'test-product-id';
    $product = RevenueCat::products()->get($productId);

    expect($product)->toBeInstanceOf(ProductData::class);
    expect($product->getId())->toBe('test-product-id');
});

test('delete returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products/test-product-id' => Http::response([
            'object' => 'product',
            'id' => 'test-product-id',
            'deleted_at' => 1658399423658,
        ], 200),
    ]);

    $productId = 'test-product-id';
    $deleted = RevenueCat::products()->delete($productId);

    expect($deleted)->toBeTrue();
});

test('get method properly encodes special characters in product id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products/test%20product%20with%20spaces%20%26%20special%20chars' => Http::response([
            'object' => 'product',
            'id' => 'test product with spaces & special chars',
            'identifier' => 'special_product',
        ], 200),
    ]);

    $productId = 'test product with spaces & special chars';
    $product = RevenueCat::products()->get($productId);

    expect($product)->toBeInstanceOf(ProductData::class);
    expect($product->getId())->toBe('test product with spaces & special chars');
});

test('delete method properly encodes special characters in product id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products/test%20product%20with%20spaces%20%26%20special%20chars' => Http::response([
            'object' => 'product',
            'id' => 'test product with spaces & special chars',
            'deleted_at' => 1658399423658,
        ], 200),
    ]);

    $productId = 'test product with spaces & special chars';
    $deleted = RevenueCat::products()->delete($productId);

    expect($deleted)->toBeTrue();
});

test('list method works with empty query array', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products' => Http::response([
            'object' => 'list',
            'items' => [],
            'next_page' => null,
            'url' => '/v2/projects/test_project/products',
        ], 200),
    ]);

    $products = RevenueCat::products()->all();

    expect($products)->toBeInstanceOf(ListPage::class);
    expect($products->items())->toBe([]);
    expect($products->nextCursor())->toBeNull();
    expect(count($products->items()))->toBe(0);
});
