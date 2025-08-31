<?php

use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\PackageData;
use BoldlineStudios\RevenueCatApi\Data\ProductData;
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

test('list returns ListPage of PackageData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages?limit=10' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'package', 'id' => 'package1', 'lookup_key' => 'basic_package', 'display_name' => 'Basic', 'position' => 1],
                ['object' => 'package', 'id' => 'package2', 'lookup_key' => 'premium_package', 'display_name' => 'Premium', 'position' => 2],
            ],
            'next_page' => null,
            'url' => '/v2/projects/test_project/packages',
        ], 200),
    ]);

    $list = RevenueCat::packages()->list(10);

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
});

test('create returns PackageData DTO', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages' => Http::response([
            'object' => 'package',
            'id' => 'new_package_id',
            'lookup_key' => 'new_package',
            'display_name' => 'New package',
            'position' => 1,
            'created_at' => 1658399423658,
        ], 201),
    ]);

    $package = RevenueCat::packages()->create('new_package', 'New package', 1);

    expect($package)->toBeInstanceOf(PackageData::class);
    expect($package->getId())->toBe('new_package_id');
    expect($package->getLookupKey())->toBe('new_package');
    expect($package->getDisplayName())->toBe('New package');
    expect($package->getPosition())->toBe(1);
    expect($package->getCreatedAtMs())->toBe(1658399423658);
});

test('get returns PackageData with encoded package id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages/test-package-id' => Http::response([
            'object' => 'package',
            'id' => 'test-package-id',
            'lookup_key' => 'premium_package',
            'display_name' => 'Premium package',
            'position' => 1,
        ], 200),
    ]);

    $packageId = 'test-package-id';
    $package = RevenueCat::packages()->get($packageId);

    expect($package)->toBeInstanceOf(PackageData::class);
    expect($package->getId())->toBe('test-package-id');
});

test('update returns PackageData DTO', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages/test-package-id' => Http::response([
            'object' => 'package',
            'id' => 'test-package-id',
            'lookup_key' => 'premium_plus_package',
            'display_name' => 'Updated premium package',
            'position' => 2,
            'created_at' => 1658399423658,
        ], 200),
    ]);

    $packageId = 'test-package-id';
    $package = RevenueCat::packages()->update($packageId, 'Updated premium package', 2);

    expect($package)->toBeInstanceOf(PackageData::class);
    expect($package->getDisplayName())->toBe('Updated premium package');
    expect($package->getPosition())->toBe(2);
    expect($package->getCreatedAtMs())->toBe(1658399423658);
});

test('delete returns true when deletion succeeds', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages/test-package-id' => Http::response([
            'object' => 'package',
            'id' => 'test-package-id',
            'deleted_at' => 1658399423658,
        ], 200),
    ]);

    $packageId = 'test-package-id';
    $deleted = RevenueCat::packages()->delete($packageId);

    expect($deleted)->toBeTrue();
});

test('listOfProducts returns ListPage of ProductData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages/test-package-id/products' => Http::response([
            'object' => 'list',
            'items' => [
                [
                    'product' => [
                        'object' => 'product',
                        'id' => 'product1',
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
                            'name' => 'App 1',
                            'created_at' => 1658399423658,
                            'type' => 'app_store',
                        ],
                        'display_name' => 'Premium Monthly 2023',
                    ],
                    'eligibility_criteria' => 'all',
                ],
                [
                    'product' => [
                        'object' => 'product',
                        'id' => 'product2',
                        'store_identifier' => 'rc_1w_100',
                        'type' => 'subscription',
                        'subscription' => [
                            'duration' => 'P1M',
                            'grace_period_duration' => 'P3D',
                            'trial_duration' => 'P1W',
                        ],
                        'one_time' => [
                            'is_consumable' => false,
                        ],
                        'created_at' => 1658399423658,
                        'app_id' => 'app1a2b3c4',
                        'app' => [
                            'object' => 'app',
                            'id' => 'app1a2b3c4',
                            'name' => 'App 2',
                            'created_at' => 1658399423659,
                            'type' => 'app_store',
                        ],
                        'display_name' => 'Premium Monthly 2023',
                    ],
                    'eligibility_criteria' => 'all',
                ],
            ],
            'next_page' => null,
            'url' => '/v2/projects/test_project/packages/test-package-id/products',
        ], 200),
    ]);

    $packageId = 'test-package-id';

    $page = RevenueCat::packages()->listOfProducts($packageId);

    expect($page)->toBeInstanceOf(ListPage::class);
    expect(count($page->items()))->toBe(2);
    expect($page->items()[0])->toBeInstanceOf(ProductData::class);
    expect($page->items()[1])->toBeInstanceOf(ProductData::class);
    expect($page->items()[0]->getDisplayName())->toBe('Premium Monthly 2023');
    expect($page->items()[1]->getDisplayName())->toBe('Premium Monthly 2023');
    expect($page->items()[0]->getApp()->getName())->toBe('App 1');
    expect($page->items()[1]->getApp()->getName())->toBe('App 2');
    expect($page->items()[0]->getApp()->getCreatedAtMs())->toBe(1658399423658);
    expect($page->items()[1]->getApp()->getCreatedAtMs())->toBe(1658399423659);
});

test('get method properly encodes special characters in package id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages/test%20package%20with%20spaces%20%26%20special%20chars' => Http::response([
            'object' => 'package',
            'id' => 'test package with spaces & special chars',
            'lookup_key' => 'special_package',
            'display_name' => 'Special package',
            'position' => 1,
        ], 200),
    ]);

    $packageId = 'test package with spaces & special chars';
    $package = RevenueCat::packages()->get($packageId);

    expect($package)->toBeInstanceOf(PackageData::class);
    expect($package->getId())->toBe('test package with spaces & special chars');
});

test('listOfProducts method properly encodes special characters in package id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages/test%20package%20with%20spaces%20%26%20special%20chars/products' => Http::response([
            'object' => 'list',
            'items' => [],
            'next_page' => null,
            'url' => '/v2/projects/test_project/packages/test%20package%20with%20spaces%20%26%20special%20chars/products',
        ], 200),
    ]);

    $packageId = 'test package with spaces & special chars';
    $page = RevenueCat::packages()->listOfProducts($packageId);

    expect($page)->toBeInstanceOf(ListPage::class);
    expect(count($page->items()))->toBe(0);
    expect($page->items())->toBe([]);
    expect($page->nextCursor())->toBeNull();
});

test('list method works with empty query array', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages' => Http::response([
            'object' => 'list',
            'items' => [],
            'next_page' => null,
            'url' => '/v2/projects/test_project/packages',
        ], 200),
    ]);

    $list = RevenueCat::packages()->list();

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(0);
});
