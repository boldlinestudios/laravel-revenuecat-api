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

test('list returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products?limit=10' => Http::response([
            'products' => [
                ['id' => 'product1', 'identifier' => 'monthly_subscription'],
                ['id' => 'product2', 'identifier' => 'yearly_subscription'],
            ],
        ], 200),
    ]);

    $response = RevenueCat::products()->list(10);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('products'))->toHaveCount(2);
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
    $response = RevenueCat::products()->create($data);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('new_product_id');
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
    $response = RevenueCat::products()->get($productId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('test-product-id');
});

test('delete returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products/test-product-id' => Http::response([], 204),
    ]);

    $productId = 'test-product-id';
    $response = RevenueCat::products()->delete($productId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->status())->toBe(204);
});

test('get method properly encodes special characters in product id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products/test%20product%20with%20spaces%20%26%20special%20chars' => Http::response([
            'id' => 'test product with spaces & special chars',
            'identifier' => 'special_product',
        ], 200),
    ]);

    $productId = 'test product with spaces & special chars';
    $response = RevenueCat::products()->get($productId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('test product with spaces & special chars');
});

test('delete method properly encodes special characters in product id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products/test%20product%20with%20spaces%20%26%20special%20chars' => Http::response([], 204),
    ]);

    $productId = 'test product with spaces & special chars';
    $response = RevenueCat::products()->delete($productId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->status())->toBe(204);
});

test('list method works with empty query array', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/products' => Http::response([
            'products' => [],
        ], 200),
    ]);

    $response = RevenueCat::products()->list();

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('products'))->toBe([]);
});
