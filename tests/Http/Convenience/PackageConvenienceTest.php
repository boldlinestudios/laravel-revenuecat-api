<?php

use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\PackageData;
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

test('getPackage calls packages()->get() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages/test-package-id' => Http::response([
            'object' => 'package',
            'id' => 'test-package-id',
            'lookup_key' => 'monthly',
            'display_name' => 'Monthly',
            'position' => 1,
        ], 200),
    ]);

    $package = RevenueCat::getPackage('test-package-id');

    expect($package)->toBeInstanceOf(PackageData::class);
    expect($package->getId())->toBe('test-package-id');
});

test('getPackageList calls packages()->list() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages?limit=10' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'package', 'id' => 'pkg1', 'lookup_key' => 'basic', 'display_name' => 'Basic', 'position' => 1],
                ['object' => 'package', 'id' => 'pkg2', 'lookup_key' => 'pro', 'display_name' => 'Pro', 'position' => 2],
            ],
        ], 200),
    ]);

    $list = RevenueCat::getPackageList(10);

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
});

test('createPackage calls packages()->create() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages' => Http::response([
            'object' => 'package',
            'id' => 'new-package-id',
            'lookup_key' => 'monthly',
            'display_name' => 'Monthly',
            'position' => 1,
            'created_at' => 1658399423658,
        ], 201),
    ]);

    $package = RevenueCat::createPackage('monthly', 'Monthly', 1);

    expect($package)->toBeInstanceOf(PackageData::class);
    expect($package->getId())->toBe('new-package-id');
    expect($package->getLookupKey())->toBe('monthly');
    expect($package->getDisplayName())->toBe('Monthly');
    expect($package->getPosition())->toBe(1);
    expect($package->getCreatedAtMs())->toBe(1658399423658);
});

test('updatePackage calls packages()->update() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages/test-package-id' => Http::response([
            'object' => 'package',
            'id' => 'test-package-id',
            'lookup_key' => 'monthly',
            'display_name' => 'Updated Package',
            'position' => 2,
            'created_at' => 1658399423658,
        ], 200),
    ]);

    $package = RevenueCat::updatePackage('test-package-id', 'Updated Package', 2);

    expect($package)->toBeInstanceOf(PackageData::class);
    expect($package->getDisplayName())->toBe('Updated Package');
    expect($package->getPosition())->toBe(2);
    expect($package->getCreatedAtMs())->toBe(1658399423658);
});

test('deletePackage calls packages()->delete() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages/test-package-id' => Http::response([
            'object' => 'package',
            'id' => 'test-package-id',
            'deleted_at' => 1658399423658,
        ], 200),
    ]);

    $deleted = RevenueCat::deletePackage('test-package-id');

    expect($deleted)->toBeTrue();
});

test('getPackageProducts calls packages()->listOfProducts() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages/test-package-id/products' => Http::response([
            'object' => 'list',
            'items' => [
                ['product' => ['object' => 'product', 'id' => 'prod1', 'store_identifier' => 'sku1', 'type' => 'subscription']],
                ['product' => ['object' => 'product', 'id' => 'prod2', 'store_identifier' => 'sku2', 'type' => 'one_time']],
            ],
        ], 200),
    ]);

    $response = RevenueCat::getPackageProducts('test-package-id');

    expect($response)->toBeInstanceOf(ListPage::class);
    expect(count($response->items()))->toBe(2);
});

test('attachPackageProducts calls packages()->attachProducts() with correct parameters', function () {
    $productAssociationList = [
        ['product_id' => 'prod1', 'eligibility_criteria' => 'all'],
        ['product_id' => 'prod2', 'eligibility_criteria' => 'google_sdk_lt_6'],
    ];

    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages/test-package-id/actions/attach_products' => Http::response([
            'object' => 'package',
            'id' => 'test-package-id',
            'lookup_key' => 'test_package',
            'display_name' => 'Test Package',
            'position' => 1,
            'created_at' => 1658399423658,
        ], 200),
    ]);

    $package = RevenueCat::attachPackageProducts('test-package-id', $productAssociationList);

    expect($package)->toBeInstanceOf(PackageData::class);
    expect($package->getId())->toBe('test-package-id');
    expect($package->getLookupKey())->toBe('test_package');
    expect($package->getDisplayName())->toBe('Test Package');
});

test('detachPackageProducts calls packages()->detachProducts() with correct parameters', function () {
    $productIds = ['prod1', 'prod2'];

    Http::fake([
        'https://api.example.com/v2/projects/test_project/packages/test-package-id/actions/detach_products' => Http::response([
            'object' => 'package',
            'id' => 'test-package-id',
            'lookup_key' => 'test_package',
            'display_name' => 'Test Package',
            'position' => 1,
            'created_at' => 1658399423658,
        ], 200),
    ]);

    $package = RevenueCat::detachPackageProducts('test-package-id', $productIds);

    expect($package)->toBeInstanceOf(PackageData::class);
    expect($package->getId())->toBe('test-package-id');
    expect($package->getLookupKey())->toBe('test_package');
    expect($package->getDisplayName())->toBe('Test Package');
});
