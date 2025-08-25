<?php

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

test('list returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products?limit=10' => Http::response([
            'object' => 'list',
            'items' => [
                ['id' => 'product1', 'store_identifier' => 'rc_1w_199'],
                ['id' => 'product2', 'store_identifier' => 'rc_1w_100'],
            ],
            'next_page' => null,
            'url' => '/v2/projects/test_project/products',
        ], 200),
    ]);

    $products = RevenueCat::products()->list(10);

    expect($products)->toBeInstanceOf(ListPage::class);
    expect(count($products->items()))->toBe(2);
});

test('create returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products' => Http::response([
            'id' => 'new_product_id',
            'identifier' => 'new_product',
            'created_at' => '2024-01-01T00:00:00Z',
        ], 201),
    ]);

    $data = ['identifier' => 'new_product', 'description' => 'New product'];
    $product = RevenueCat::products()->create($data);

    expect($product)->toBeInstanceOf(ProductData::class);
    expect($product->getId())->toBe('new_product_id');
});

test('get returns response from client with encoded product id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products/test-product-id' => Http::response([
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
            'object' => 'app',
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
            'object' => 'app',
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
            'products' => [],
        ], 200),
    ]);

    $products = RevenueCat::products()->list();

    expect($products)->toBeInstanceOf(ListPage::class);
    expect(count($products->items()))->toBe(0);
});
