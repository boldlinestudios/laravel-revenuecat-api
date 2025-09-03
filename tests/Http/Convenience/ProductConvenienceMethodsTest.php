<?php

use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\ProductData;
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

test('getProduct calls products()->get() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products/test-product-id' => Http::response([
            'object' => 'product',
            'id' => 'test-product-id',
            'store_identifier' => 'sku',
            'type' => 'subscription',
        ], 200),
    ]);

    $product = RevenueCat::getProduct('test-product-id');

    expect($product)->toBeInstanceOf(ProductData::class);
    expect($product->getId())->toBe('test-product-id');
});

test('listProducts calls products()->all() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products?limit=10' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'product', 'id' => 'prod1', 'store_identifier' => 'sku1', 'type' => 'subscription', 'display_name' => 'Product 1'],
                ['object' => 'product', 'id' => 'prod2', 'store_identifier' => 'sku2', 'type' => 'one_time', 'display_name' => 'Product 2'],
            ],
        ], 200),
    ]);

    $list = RevenueCat::listProducts(10);

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
    expect($list->items()[0]->getDisplayName())->toBe('Product 1');
    expect($list->items()[1]->getDisplayName())->toBe('Product 2');
});

test('createProduct calls products()->create() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products' => Http::response([
            'object' => 'product',
            'id' => 'new_product_id',
            'store_identifier' => 'rc_1w_199"',
            'type' => 'subscription',
            'created_at' => 1658399423658,
            'app_id' => 'test_app_id',
            'display_name' => 'New product',
        ], 201),
    ]);

    $product = RevenueCat::createProduct('rc_1w_199"', 'test_app_id', 'subscription', 'New Product');

    expect($product)->toBeInstanceOf(ProductData::class);
    expect($product->getStoreIdentifier())->toBe('rc_1w_199"');
    expect($product->getId())->toBe('new_product_id');
    expect($product->getDisplayName())->toBe('New product');
    expect($product->getType())->toBe('subscription');
});

test('deleteProduct calls products()->delete() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products/test-product-id' => Http::response([
            'object' => 'product',
            'id' => 'test-product-id',
            'deleted_at' => 1658399423658,
        ], 200),
    ]);

    $deleted = RevenueCat::deleteProduct('test-product-id');
    expect($deleted)->toBeTrue();
});
