<?php

use BoldlineStudios\RevenueCatApi\Data\AppData;
use BoldlineStudios\RevenueCatApi\Data\CustomerData;
use BoldlineStudios\RevenueCatApi\Data\EntitlementData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\OfferingData;
use BoldlineStudios\RevenueCatApi\Data\PackageData;
use BoldlineStudios\RevenueCatApi\Data\ProductData;
use BoldlineStudios\RevenueCatApi\Data\PurchaseData;
use BoldlineStudios\RevenueCatApi\Data\SubscriptionData;
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

describe('App Convenience Methods', function () {
    test('getApp calls apps()->get() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response([
                'object' => 'app',
                'id' => 'test-app-id',
                'name' => 'Test App',
            ], 200),
        ]);

        $response = RevenueCat::getApp('test-app-id');

        expect($response)->toBeInstanceOf(AppData::class);
        expect($response->getId())->toBe('test-app-id');
    });

    test('getAppList calls apps()->list() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps?limit=10' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'app1', 'name' => 'App 1'],
                    ['id' => 'app2', 'name' => 'App 2'],
                ],
            ], 200),
        ]);

        $response = RevenueCat::getAppList(10);

        expect($response)->toBeInstanceOf(ListPage::class);
        expect(count($response->items()))->toBe(2);
    });

    test('createApp calls apps()->create() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps' => Http::response([
                'object' => 'app',
                'id' => 'new-app-id',
                'name' => 'New App',
            ], 201),
        ]);

        $data = ['name' => 'New App', 'type' => 'app_store'];
        $app = RevenueCat::createApp($data);

        expect($app)->toBeInstanceOf(AppData::class);
        expect($app->getId())->toBe('new-app-id');
    });

    test('updateApp calls apps()->update() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response([
                'object' => 'app',
                'id' => 'test-app-id',
                'name' => 'Updated App',
            ], 200),
        ]);

        $data = ['name' => 'Updated App'];
        $app = RevenueCat::updateApp('test-app-id', $data);

        expect($app)->toBeInstanceOf(AppData::class);
        expect($app->getName())->toBe('Updated App');
    });

    test('deleteApp calls apps()->delete() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response([
                'object' => 'app',
                'id' => 'test-app-id',
                'deleted_at' => 1658399423658,
            ], 200),
        ]);

        $deleted = RevenueCat::deleteApp('test-app-id');

        expect($deleted)->toBeTrue();
    });

    test('getAppStoreKitConfig calls apps()->storeKitConfig() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps/test-app-id/store_kit_config' => Http::response([
                'config' => 'store_kit_configuration_data',
            ], 200),
        ]);

        $response = RevenueCat::getAppStoreKitConfig('test-app-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('config'))->toBe('store_kit_configuration_data');
    });

    test('getAppPublicKeys calls apps()->listOfPublicKeys() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps/test-app-id/public_api_keys' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'key1', 'key' => 'pk_test_123'],
                ],
            ], 200),
        ]);

        $response = RevenueCat::getAppPublicKeys('test-app-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(1);
    });
});

describe('Customer Convenience Methods', function () {
    test('getCustomer calls customers()->get() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/customers/test-customer-id' => Http::response([
                'object' => 'customer',
                'id' => 'test-customer-id',
            ], 200),
        ]);

        $customer = RevenueCat::getCustomer('test-customer-id');

        expect($customer)->toBeInstanceOf(CustomerData::class);
        expect($customer->getId())->toBe('test-customer-id');
    });

    test('getCustomerList calls customers()->list() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/customers?limit=10' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'customer1'],
                    ['id' => 'customer2'],
                ],
            ], 200),
        ]);

        $list = RevenueCat::getCustomerList(10);

        expect($list)->toBeInstanceOf(ListPage::class);
        expect(count($list->items()))->toBe(2);
    });

    test('createCustomer calls customers()->create() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/customers' => Http::response([
                'object' => 'customer',
                'id' => 'new-customer-id',
            ], 201),
        ]);

        $customer = RevenueCat::createCustomer('new-customer-id', [
            ['name' => '$email', 'value' => 'test@example.com'],
        ]);

        expect($customer)->toBeInstanceOf(CustomerData::class);
        expect($customer->getId())->toBe('new-customer-id');
    });

    test('deleteCustomer calls customers()->delete() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/customers/test-customer-id' => Http::response([
                'object' => 'customer',
                'id' => 'test-customer-id',
                'deleted_at' => 1658399423658,
            ], 200),
        ]);

        $deleted = RevenueCat::deleteCustomer('test-customer-id');

        expect($deleted)->toBeTrue();
    });

    test('getCustomerSubscriptions calls customers()->listOfSubscriptions() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/customers/test-customer-id/subscriptions' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'sub1', 'status' => 'active'],
                ],
            ], 200),
        ]);

        $response = RevenueCat::getCustomerSubscriptions('test-customer-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(1);
    });

    test('getCustomerPurchases calls customers()->listOfPurchases() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/customers/test-customer-id/purchases' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'purchase1', 'amount' => 9.99],
                ],
            ], 200),
        ]);

        $response = RevenueCat::getCustomerPurchases('test-customer-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(1);
    });

    test('getCustomerActiveEntitlements calls customers()->listOfActiveEntitlements() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/customers/test-customer-id/active_entitlements' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'entitlement1', 'identifier' => 'premium'],
                ],
            ], 200),
        ]);

        $response = RevenueCat::getCustomerActiveEntitlements('test-customer-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(1);
    });

    test('getCustomerAliases calls customers()->listOfAliases() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/customers/test-customer-id/aliases' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'alias1', 'alias' => 'john_doe'],
                ],
            ], 200),
        ]);

        $response = RevenueCat::getCustomerAliases('test-customer-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(1);
    });

    test('getCustomerVirtualCurrencyBalances calls customers()->listOfVirtualCurrencyBalances() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/customers/test-customer-id/virtual_currencies' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'balance1', 'currency' => 'coins', 'balance' => 100],
                ],
            ], 200),
        ]);

        $response = RevenueCat::getCustomerVirtualCurrencyBalances('test-customer-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(1);
    });

    test('getCustomerAttributes calls customers()->listOfAttributes() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/customers/test-customer-id/attributes' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'attr1', 'key' => 'preference', 'value' => 'dark_mode'],
                ],
            ], 200),
        ]);

        $response = RevenueCat::getCustomerAttributes('test-customer-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(1);
    });
});

describe('Entitlement Convenience Methods', function () {
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

    test('getEntitlementList calls entitlements()->list() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/entitlements?limit=10' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'ent1'],
                    ['id' => 'ent2'],
                ],
            ], 200),
        ]);

        $list = RevenueCat::getEntitlementList(10);

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

    test('getEntitlementProducts calls entitlements()->listOfProducts() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/entitlements/test-entitlement-id/products' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'prod1', 'name' => 'Product 1'],
                    ['id' => 'prod2', 'name' => 'Product 2'],
                ],
            ], 200),
        ]);

        $response = RevenueCat::getEntitlementProducts('test-entitlement-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(2);
    });
});

describe('Offering Convenience Methods', function () {
    test('getOffering calls offerings()->get() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/offerings/test-offering-id' => Http::response([
                'object' => 'offering',
                'id' => 'test-offering-id',
                'lookup_key' => 'basic',
                'display_name' => 'Basic Plan',
            ], 200),
        ]);

        $response = RevenueCat::getOffering('test-offering-id');

        expect($response)->toBeInstanceOf(OfferingData::class);
        expect($response->getId())->toBe('test-offering-id');
    });

    test('getOfferingList calls offerings()->list() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/offerings?limit=10' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'off1', 'lookup_key' => 'basic', 'display_name' => 'Basic Plan'],
                    ['id' => 'off2', 'lookup_key' => 'pro', 'display_name' => 'Pro Plan'],
                ],
            ], 200),
        ]);

        $response = RevenueCat::getOfferingList(10);

        expect($response)->toBeInstanceOf(ListPage::class);
        expect(count($response->items()))->toBe(2);
    });

    test('createOffering calls offerings()->create() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/offerings' => Http::response([
                'object' => 'offering',
                'id' => 'new-offering-id',
                'lookup_key' => 'basic',
                'display_name' => 'New Plan',
            ], 201),
        ]);

        $data = ['lookup_key' => 'basic', 'display_name' => 'New Plan'];
        $response = RevenueCat::createOffering($data);

        expect($response)->toBeInstanceOf(OfferingData::class);
        expect($response->getId())->toBe('new-offering-id');
    });

    test('updateOffering calls offerings()->update() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/offerings/test-offering-id' => Http::response([
                'object' => 'offering',
                'id' => 'test-offering-id',
                'lookup_key' => 'basic',
                'display_name' => 'Updated Plan',
            ], 200),
        ]);

        $data = ['lookup_key' => 'basic', 'display_name' => 'Updated Plan'];
        $offering = RevenueCat::updateOffering('test-offering-id', $data);

        expect($offering)->toBeInstanceOf(OfferingData::class);
        expect($offering->getDisplayName())->toBe('Updated Plan');
    });

    test('deleteOffering calls offerings()->delete() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/offerings/test-offering-id' => Http::response([
                'object' => 'offering',
                'id' => 'test-offering-id',
                'deleted_at' => 1658399423658,
            ], 200),
        ]);

        $deleted = RevenueCat::deleteOffering('test-offering-id');

        expect($deleted)->toBeTrue();
    });
});

describe('Package Convenience Methods', function () {
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
                    ['id' => 'pkg1', 'lookup_key' => 'basic', 'display_name' => 'Basic', 'position' => 1],
                    ['id' => 'pkg2', 'lookup_key' => 'pro', 'display_name' => 'Pro', 'position' => 2],
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
            ], 201),
        ]);

        $data = ['lookup_key' => 'monthly', 'display_name' => 'Monthly', 'position' => 1];
        $package = RevenueCat::createPackage($data);

        expect($package)->toBeInstanceOf(PackageData::class);
        expect($package->getId())->toBe('new-package-id');
    });

    test('updatePackage calls packages()->update() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/packages/test-package-id' => Http::response([
                'object' => 'package',
                'id' => 'test-package-id',
                'display_name' => 'Updated Package',
                'position' => 2,
            ], 200),
        ]);

        $data = ['display_name' => 'Updated Package', 'position' => 2];
        $package = RevenueCat::updatePackage('test-package-id', $data);

        expect($package)->toBeInstanceOf(PackageData::class);
        expect($package->getDisplayName())->toBe('Updated Package');
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
                    ['id' => 'prod1', 'store_identifier' => 'sku1', 'type' => 'subscription'],
                    ['id' => 'prod2', 'store_identifier' => 'sku2', 'type' => 'one_time'],
                ],
            ], 200),
        ]);

        $response = RevenueCat::getPackageProducts('test-package-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(2);
    });
});

describe('Product Convenience Methods', function () {
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

    test('getProductList calls products()->list() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/products?limit=10' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'prod1', 'store_identifier' => 'sku1', 'type' => 'subscription'],
                    ['id' => 'prod2', 'store_identifier' => 'sku2', 'type' => 'one_time'],
                ],
            ], 200),
        ]);

        $list = RevenueCat::getProductList(10);

        expect($list)->toBeInstanceOf(ListPage::class);
        expect(count($list->items()))->toBe(2);
    });

    test('createProduct calls products()->create() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/products' => Http::response([
                'object' => 'product',
                'id' => 'new-product-id',
                'store_identifier' => 'sku',
                'type' => 'subscription',
            ], 201),
        ]);

        $data = [
            'store_identifier' => 'sku',
            'app_id' => 'app123',
            'type' => 'subscription',
            'display_name' => 'Premium',
        ];
        $product = RevenueCat::createProduct($data);

        expect($product)->toBeInstanceOf(ProductData::class);
        expect($product->getId())->toBe('new-product-id');
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
});

describe('Project Convenience Methods', function () {
    test('getProjectList calls projects()->list() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects?limit=10' => Http::response([
                'object' => 'list',
                'projects' => [
                    ['id' => 'proj1', 'name' => 'Project 1'],
                    ['id' => 'proj2', 'name' => 'Project 2'],
                ],
                'url' => '/v2/projects',
            ], 200),
        ]);

        $list = RevenueCat::getProjectList(10);

        expect($list)->toBeInstanceOf(ListPage::class);
        expect(count($list->items()))->toBe(2);
    });
});

describe('Purchase Convenience Methods', function () {
    test('getPurchase calls purchases()->get() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/purchases/test-purchase-id' => Http::response([
                'object' => 'purchase',
                'id' => 'test-purchase-id',
                'customer_id' => 'cust',
                'product_id' => 'prod',
                'purchased_at' => 1658399423658,
            ], 200),
        ]);

        $purchase = RevenueCat::getPurchase('test-purchase-id');

        expect($purchase)->toBeInstanceOf(PurchaseData::class);
        expect($purchase->getId())->toBe('test-purchase-id');
    });

    test('getPurchaseEntitlements calls purchases()->listOfEntitlements() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/purchases/test-purchase-id/entitlements' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'ent1', 'identifier' => 'premium'],
                    ['id' => 'ent2', 'identifier' => 'basic'],
                ],
            ], 200),
        ]);

        $response = RevenueCat::getPurchaseEntitlements('test-purchase-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(2);
    });
});

describe('Subscription Convenience Methods', function () {
    test('getSubscription calls subscriptions()->get() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id' => Http::response([
                'object' => 'subscription',
                'id' => 'test-subscription-id',
                'status' => 'active',
            ], 200),
        ]);

        $sub = RevenueCat::getSubscription('test-subscription-id');

        expect($sub)->toBeInstanceOf(SubscriptionData::class);

    });

    test('getSubscriptionEntitlements calls subscriptions()->listOfEntitlements() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/entitlements' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'ent1', 'identifier' => 'premium'],
                    ['id' => 'ent2', 'identifier' => 'basic'],
                ],
            ], 200),
        ]);

        $response = RevenueCat::getSubscriptionEntitlements('test-subscription-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(2);
    });

    test('getSubscriptionTransactions calls subscriptions()->listOfTransactions() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/transactions' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'txn1', 'amount' => 9.99],
                    ['id' => 'txn2', 'amount' => 9.99],
                ],
            ], 200),
        ]);

        $response = RevenueCat::getSubscriptionTransactions('test-subscription-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(2);
    });

    test('getSubscriptionCustomerPortalUrl calls subscriptions()->getCustomerPortalUrl() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/authenticated_management_url' => Http::response([
                'url' => 'https://portal.example.com/access/123',
            ], 200),
        ]);

        $response = RevenueCat::getSubscriptionCustomerPortalUrl('test-subscription-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('url'))->toBe('https://portal.example.com/access/123');
    });

    test('cancelWebBillingSubscription calls subscriptions()->cancelWebBillingSubscription() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/actions/cancel' => Http::response([
                'object' => 'subscription',
                'id' => 'test-subscription-id',
                'status' => 'cancelled',
            ], 200),
        ]);

        $response = RevenueCat::cancelWebBillingSubscription('test-subscription-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('status'))->toBe('cancelled');
    });

    test('refundWebBillingSubscription calls subscriptions()->refundWebBillingSubscription() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/actions/refund' => Http::response([
                'object' => 'subscription',
                'id' => 'test-subscription-id',
                'status' => 'refunded',
            ], 200),
        ]);

        $response = RevenueCat::refundWebBillingSubscription('test-subscription-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('status'))->toBe('refunded');
    });
});

describe('Trait Integration', function () {
    test('convenience methods delegate to the correct endpoint methods', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps/test-id' => Http::response(['object' => 'app', 'id' => 'test-id'], 200),
            'https://api.example.com/v2/projects/test_project/customers/test-id' => Http::response(['object' => 'customer', 'id' => 'test-id'], 200),
            'https://api.example.com/v2/projects/test_project/entitlements/test-id' => Http::response(['object' => 'entitlement', 'id' => 'test-id'], 200),
            'https://api.example.com/v2/projects/test_project/offerings/test-id' => Http::response(['object' => 'offering', 'id' => 'test-id', 'lookup_key' => 'lk', 'display_name' => 'dn'], 200),
            'https://api.example.com/v2/projects/test_project/packages/test-id' => Http::response(['object' => 'package', 'id' => 'test-id', 'lookup_key' => 'lk', 'display_name' => 'dn', 'position' => 1], 200),
            'https://api.example.com/v2/projects/test_project/products/test-id' => Http::response(['object' => 'product', 'id' => 'test-id', 'store_identifier' => 'sku', 'type' => 'subscription'], 200),
            'https://api.example.com/v2/projects?limit=1' => Http::response(['object' => 'list', 'projects' => [], 'url' => '/v2/projects'], 200),
            'https://api.example.com/v2/projects/test_project/purchases/test-id' => Http::response(['object' => 'purchase', 'id' => 'test-id', 'customer_id' => 'c', 'product_id' => 'p', 'purchased_at' => 1], 200),
            'https://api.example.com/v2/projects/test_project/subscriptions/test-id' => Http::response(['object' => 'subscription', 'id' => 'test-id'], 200),
        ]);

        // Test that all endpoint methods work via facade
        $appResponse = RevenueCat::getApp('test-id');
        $customerResponse = RevenueCat::getCustomer('test-id');
        $entitlementResponse = RevenueCat::getEntitlement('test-id');
        $offeringResponse = RevenueCat::getOffering('test-id');
        $packageResponse = RevenueCat::getPackage('test-id');
        $productResponse = RevenueCat::getProduct('test-id');
        $projectResponse = RevenueCat::getProjectList(1);
        $purchaseResponse = RevenueCat::getPurchase('test-id');
        $subscriptionResponse = RevenueCat::getSubscription('test-id');

        expect($appResponse)->toBeInstanceOf(AppData::class);
        expect($customerResponse)->toBeInstanceOf(CustomerData::class);
        expect($entitlementResponse)->toBeInstanceOf(EntitlementData::class);
        expect($offeringResponse)->toBeInstanceOf(OfferingData::class);
        expect($packageResponse)->toBeInstanceOf(PackageData::class);
        expect($productResponse)->toBeInstanceOf(ProductData::class);
        expect($projectResponse)->toBeInstanceOf(ListPage::class);
        expect($purchaseResponse)->toBeInstanceOf(PurchaseData::class);
        expect($subscriptionResponse)->toBeInstanceOf(SubscriptionData::class);
    });
});
