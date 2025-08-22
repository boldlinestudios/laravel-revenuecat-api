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
        'https://api.example.com/v2/projects/test_project/packages?limit=10' => Http::response([
            'packages' => [
                ['id' => 'package1', 'identifier' => 'basic_package'],
                ['id' => 'package2', 'identifier' => 'premium_package'],
            ],
        ], 200),
    ]);

    $response = RevenueCat::packages()->list(10);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('packages'))->toHaveCount(2);
});

test('create returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages' => Http::response([
            'id' => 'new_package_id',
            'identifier' => 'new_package',
            'created_at' => '2024-01-01T00:00:00Z',
        ], 201),
    ]);

    $data = ['identifier' => 'new_package', 'description' => 'New package'];
    $response = RevenueCat::packages()->create($data);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('new_package_id');
});

test('get returns response from client with encoded package id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages/test-package-id' => Http::response([
            'id' => 'test-package-id',
            'identifier' => 'premium_package',
            'description' => 'Premium package',
        ], 200),
    ]);

    $packageId = 'test-package-id';
    $response = RevenueCat::packages()->get($packageId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('test-package-id');
});

test('update returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages/test-package-id' => Http::response([
            'id' => 'test-package-id',
            'identifier' => 'premium_plus_package',
            'description' => 'Updated premium package',
        ], 200),
    ]);

    $packageId = 'test-package-id';
    $data = ['description' => 'Updated premium package'];
    $response = RevenueCat::packages()->update($packageId, $data);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('description'))->toBe('Updated premium package');
});

test('delete returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages/test-package-id' => Http::response([], 204),
    ]);

    $packageId = 'test-package-id';
    $response = RevenueCat::packages()->delete($packageId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->status())->toBe(204);
});

test('listOfProducts returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages/test-package-id/products' => Http::response([
            'products' => [
                ['id' => 'product1', 'identifier' => 'monthly_sub'],
                ['id' => 'product2', 'identifier' => 'yearly_sub'],
            ],
        ], 200),
    ]);

    $packageId = 'test-package-id';
    $response = RevenueCat::packages()->listOfProducts($packageId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('products'))->toHaveCount(2);
});

test('get method properly encodes special characters in package id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages/test%20package%20with%20spaces%20%26%20special%20chars' => Http::response([
            'id' => 'test package with spaces & special chars',
            'identifier' => 'special_package',
        ], 200),
    ]);

    $packageId = 'test package with spaces & special chars';
    $response = RevenueCat::packages()->get($packageId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('test package with spaces & special chars');
});

test('listOfProducts method properly encodes special characters in package id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages/test%20package%20with%20spaces%20%26%20special%20chars/products' => Http::response([
            'products' => [],
        ], 200),
    ]);

    $packageId = 'test package with spaces & special chars';
    $response = RevenueCat::packages()->listOfProducts($packageId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('products'))->toBe([]);
});

test('list method works with empty query array', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages' => Http::response([
            'packages' => [],
        ], 200),
    ]);

    $response = RevenueCat::packages()->list();

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('packages'))->toBe([]);
});
