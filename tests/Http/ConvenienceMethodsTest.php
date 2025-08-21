<?php

use BoldlineStudios\RevenueCatApi\Facades\RevenueCatClient;
use BoldlineStudios\RevenueCatApi\Http\Concerns\ConvenienceMethods;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

// Create a test class that uses the trait
class TestConvenienceMethods
{
    use ConvenienceMethods;

    public function apps()
    {
        return RevenueCatClient::apps();
    }

    public function customers()
    {
        return RevenueCatClient::customers();
    }

    public function entitlements()
    {
        return RevenueCatClient::entitlements();
    }

    public function offerings()
    {
        return RevenueCatClient::offerings();
    }

    public function packages()
    {
        return RevenueCatClient::packages();
    }

    public function products()
    {
        return RevenueCatClient::products();
    }

    public function projects()
    {
        return RevenueCatClient::projects();
    }

    public function purchases()
    {
        return RevenueCatClient::purchases();
    }

    public function subscriptions()
    {
        return RevenueCatClient::subscriptions();
    }
}

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

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getApp('test-app-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('id'))->toBe('test-app-id');
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

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getAppList(10);

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(2);
    });

    test('createApp calls apps()->create() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps' => Http::response([
                'object' => 'app',
                'id' => 'new-app-id',
                'name' => 'New App',
            ], 201),
        ]);

        $testClass = new TestConvenienceMethods;
        $data = ['name' => 'New App', 'type' => 'app_store'];
        $response = $testClass->createApp($data);

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('id'))->toBe('new-app-id');
    });

    test('updateApp calls apps()->update() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response([
                'object' => 'app',
                'id' => 'test-app-id',
                'name' => 'Updated App',
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $data = ['name' => 'Updated App'];
        $response = $testClass->updateApp('test-app-id', $data);

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('name'))->toBe('Updated App');
    });

    test('deleteApp calls apps()->delete() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response([
                'object' => 'app',
                'id' => 'test-app-id',
                'deleted_at' => 1658399423658,
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->deleteApp('test-app-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('deleted_at'))->toBe(1658399423658);
    });

    test('getAppStoreKitConfig calls apps()->storeKitConfig() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps/test-app-id/store_kit_config' => Http::response([
                'config' => 'store_kit_configuration_data',
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getAppStoreKitConfig('test-app-id');

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

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getAppPublicKeys('test-app-id');

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
                'name' => 'Test Customer',
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getCustomer('test-customer-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('id'))->toBe('test-customer-id');
    });

    test('getCustomerList calls customers()->list() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/customers?limit=10' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'customer1', 'name' => 'Customer 1'],
                    ['id' => 'customer2', 'name' => 'Customer 2'],
                ],
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getCustomerList(10);

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(2);
    });

    test('createCustomer calls customers()->create() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/customers' => Http::response([
                'object' => 'customer',
                'id' => 'new-customer-id',
                'name' => 'New Customer',
            ], 201),
        ]);

        $testClass = new TestConvenienceMethods;
        $data = ['name' => 'New Customer', 'email' => 'test@example.com'];
        $response = $testClass->createCustomer($data);

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('id'))->toBe('new-customer-id');
    });

    test('deleteCustomer calls customers()->delete() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/customers/test-customer-id' => Http::response([
                'object' => 'customer',
                'id' => 'test-customer-id',
                'deleted_at' => 1658399423658,
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->deleteCustomer('test-customer-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('deleted_at'))->toBe(1658399423658);
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

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getCustomerSubscriptions('test-customer-id');

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

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getCustomerPurchases('test-customer-id');

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

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getCustomerActiveEntitlements('test-customer-id');

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

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getCustomerAliases('test-customer-id');

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

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getCustomerVirtualCurrencyBalances('test-customer-id');

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

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getCustomerAttributes('test-customer-id');

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
                'identifier' => 'premium',
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getEntitlement('test-entitlement-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('id'))->toBe('test-entitlement-id');
    });

    test('getEntitlementList calls entitlements()->list() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/entitlements?limit=10' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'ent1', 'identifier' => 'premium'],
                    ['id' => 'ent2', 'identifier' => 'basic'],
                ],
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getEntitlementList(10);

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(2);
    });

    test('createEntitlement calls entitlements()->create() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/entitlements' => Http::response([
                'object' => 'entitlement',
                'id' => 'new-entitlement-id',
                'identifier' => 'premium',
            ], 201),
        ]);

        $testClass = new TestConvenienceMethods;
        $data = ['identifier' => 'premium', 'name' => 'Premium Access'];
        $response = $testClass->createEntitlement($data);

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('id'))->toBe('new-entitlement-id');
    });

    test('updateEntitlement calls entitlements()->update() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/entitlements/test-entitlement-id' => Http::response([
                'object' => 'entitlement',
                'id' => 'test-entitlement-id',
                'identifier' => 'updated_premium',
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $data = ['identifier' => 'updated_premium'];
        $response = $testClass->updateEntitlement('test-entitlement-id', $data);

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('identifier'))->toBe('updated_premium');
    });

    test('deleteEntitlement calls entitlements()->delete() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/entitlements/test-entitlement-id' => Http::response([
                'object' => 'entitlement',
                'id' => 'test-entitlement-id',
                'deleted_at' => 1658399423658,
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->deleteEntitlement('test-entitlement-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('deleted_at'))->toBe(1658399423658);
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

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getEntitlementProducts('test-entitlement-id');

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
                'name' => 'Basic Plan',
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getOffering('test-offering-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('id'))->toBe('test-offering-id');
    });

    test('getOfferingList calls offerings()->list() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/offerings?limit=10' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'off1', 'name' => 'Basic Plan'],
                    ['id' => 'off2', 'name' => 'Premium Plan'],
                ],
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getOfferingList(10);

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(2);
    });

    test('createOffering calls offerings()->create() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/offerings' => Http::response([
                'object' => 'offering',
                'id' => 'new-offering-id',
                'name' => 'New Plan',
            ], 201),
        ]);

        $testClass = new TestConvenienceMethods;
        $data = ['name' => 'New Plan', 'description' => 'A new offering'];
        $response = $testClass->createOffering($data);

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('id'))->toBe('new-offering-id');
    });

    test('updateOffering calls offerings()->update() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/offerings/test-offering-id' => Http::response([
                'object' => 'offering',
                'id' => 'test-offering-id',
                'name' => 'Updated Plan',
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $data = ['name' => 'Updated Plan'];
        $response = $testClass->updateOffering('test-offering-id', $data);

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('name'))->toBe('Updated Plan');
    });

    test('deleteOffering calls offerings()->delete() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/offerings/test-offering-id' => Http::response([
                'object' => 'offering',
                'id' => 'test-offering-id',
                'deleted_at' => 1658399423658,
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->deleteOffering('test-offering-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('deleted_at'))->toBe(1658399423658);
    });
});

describe('Package Convenience Methods', function () {
    test('getPackage calls packages()->get() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/packages/test-package-id' => Http::response([
                'object' => 'package',
                'id' => 'test-package-id',
                'name' => 'Premium Package',
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getPackage('test-package-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('id'))->toBe('test-package-id');
    });

    test('getPackageList calls packages()->list() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/packages?limit=10' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'pkg1', 'name' => 'Basic Package'],
                    ['id' => 'pkg2', 'name' => 'Premium Package'],
                ],
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getPackageList(10);

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(2);
    });

    test('createPackage calls packages()->create() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/packages' => Http::response([
                'object' => 'package',
                'id' => 'new-package-id',
                'name' => 'New Package',
            ], 201),
        ]);

        $testClass = new TestConvenienceMethods;
        $data = ['name' => 'New Package', 'description' => 'A new package'];
        $response = $testClass->createPackage($data);

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('id'))->toBe('new-package-id');
    });

    test('updatePackage calls packages()->update() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/packages/test-package-id' => Http::response([
                'object' => 'package',
                'id' => 'test-package-id',
                'name' => 'Updated Package',
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $data = ['name' => 'Updated Package'];
        $response = $testClass->updatePackage('test-package-id', $data);

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('name'))->toBe('Updated Package');
    });

    test('deletePackage calls packages()->delete() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/packages/test-package-id' => Http::response([
                'object' => 'package',
                'id' => 'test-package-id',
                'deleted_at' => 1658399423658,
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->deletePackage('test-package-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('deleted_at'))->toBe(1658399423658);
    });

    test('getPackageProducts calls packages()->listOfProducts() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/packages/test-package-id/products' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'prod1', 'name' => 'Product 1'],
                    ['id' => 'prod2', 'name' => 'Product 2'],
                ],
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getPackageProducts('test-package-id');

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
                'name' => 'Test Product',
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getProduct('test-product-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('id'))->toBe('test-product-id');
    });

    test('getProductList calls products()->list() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/products?limit=10' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'prod1', 'name' => 'Product 1'],
                    ['id' => 'prod2', 'name' => 'Product 2'],
                ],
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getProductList(10);

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(2);
    });

    test('createProduct calls products()->create() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/products' => Http::response([
                'object' => 'product',
                'id' => 'new-product-id',
                'name' => 'New Product',
            ], 201),
        ]);

        $testClass = new TestConvenienceMethods;
        $data = ['name' => 'New Product', 'description' => 'A new product'];
        $response = $testClass->createProduct($data);

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('id'))->toBe('new-product-id');
    });

    test('deleteProduct calls products()->delete() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/products/test-product-id' => Http::response([
                'object' => 'product',
                'id' => 'test-product-id',
                'deleted_at' => 1658399423658,
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->deleteProduct('test-product-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('deleted_at'))->toBe(1658399423658);
    });
});

describe('Project Convenience Methods', function () {
    test('getProjectList calls projects()->list() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects?limit=10' => Http::response([
                'object' => 'list',
                'items' => [
                    ['id' => 'proj1', 'name' => 'Project 1'],
                    ['id' => 'proj2', 'name' => 'Project 2'],
                ],
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getProjectList(10);

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('items'))->toHaveCount(2);
    });
});

describe('Purchase Convenience Methods', function () {
    test('getPurchase calls purchases()->get() with correct parameters', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/purchases/test-purchase-id' => Http::response([
                'object' => 'purchase',
                'id' => 'test-purchase-id',
                'amount' => 9.99,
            ], 200),
        ]);

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getPurchase('test-purchase-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('id'))->toBe('test-purchase-id');
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

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getPurchaseEntitlements('test-purchase-id');

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

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getSubscription('test-subscription-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('id'))->toBe('test-subscription-id');
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

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getSubscriptionEntitlements('test-subscription-id');

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

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getSubscriptionTransactions('test-subscription-id');

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

        $testClass = new TestConvenienceMethods;
        $response = $testClass->getSubscriptionCustomerPortalUrl('test-subscription-id');

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

        $testClass = new TestConvenienceMethods;
        $response = $testClass->cancelWebBillingSubscription('test-subscription-id');

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

        $testClass = new TestConvenienceMethods;
        $response = $testClass->refundWebBillingSubscription('test-subscription-id');

        expect($response)->toBeInstanceOf(Response::class);
        expect($response->successful())->toBeTrue();
        expect($response->json('status'))->toBe('refunded');
    });
});

describe('Trait Integration', function () {
    test('trait methods are available on classes that use it', function () {
        $testClass = new TestConvenienceMethods;

        // Verify the trait methods exist
        expect(method_exists($testClass, 'getApp'))->toBeTrue();
        expect(method_exists($testClass, 'getCustomer'))->toBeTrue();
        expect(method_exists($testClass, 'createApp'))->toBeTrue();
        expect(method_exists($testClass, 'getEntitlement'))->toBeTrue();
        expect(method_exists($testClass, 'getOffering'))->toBeTrue();
        expect(method_exists($testClass, 'getPackage'))->toBeTrue();
        expect(method_exists($testClass, 'getProduct'))->toBeTrue();
        expect(method_exists($testClass, 'getProjectList'))->toBeTrue();
        expect(method_exists($testClass, 'getPurchase'))->toBeTrue();
        expect(method_exists($testClass, 'getSubscription'))->toBeTrue();
    });

    test('trait methods delegate to the correct endpoint methods', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps/test-id' => Http::response(['id' => 'test-id'], 200),
            'https://api.example.com/v2/projects/test_project/customers/test-id' => Http::response(['id' => 'test-id'], 200),
            'https://api.example.com/v2/projects/test_project/entitlements/test-id' => Http::response(['id' => 'test-id'], 200),
            'https://api.example.com/v2/projects/test_project/offerings/test-id' => Http::response(['id' => 'test-id'], 200),
            'https://api.example.com/v2/projects/test_project/packages/test-id' => Http::response(['id' => 'test-id'], 200),
            'https://api.example.com/v2/projects/test_project/products/test-id' => Http::response(['id' => 'test-id'], 200),
            'https://api.example.com/v2/projects*' => Http::response(['object' => 'list', 'items' => []], 200),
            'https://api.example.com/v2/projects/test_project/purchases/test-id' => Http::response(['id' => 'test-id'], 200),
            'https://api.example.com/v2/projects/test_project/subscriptions/test-id' => Http::response(['id' => 'test-id'], 200),
        ]);

        $testClass = new TestConvenienceMethods;

        // Test that all endpoint methods work
        $appResponse = $testClass->getApp('test-id');
        $customerResponse = $testClass->getCustomer('test-id');
        $entitlementResponse = $testClass->getEntitlement('test-id');
        $offeringResponse = $testClass->getOffering('test-id');
        $packageResponse = $testClass->getPackage('test-id');
        $productResponse = $testClass->getProduct('test-id');
        $projectResponse = $testClass->getProjectList(1);
        $purchaseResponse = $testClass->getPurchase('test-id');
        $subscriptionResponse = $testClass->getSubscription('test-id');

        expect($appResponse->successful())->toBeTrue();
        expect($customerResponse->successful())->toBeTrue();
        expect($entitlementResponse->successful())->toBeTrue();
        expect($offeringResponse->successful())->toBeTrue();
        expect($packageResponse->successful())->toBeTrue();
        expect($productResponse->successful())->toBeTrue();
        expect($projectResponse->successful())->toBeTrue();
        expect($purchaseResponse->successful())->toBeTrue();
        expect($subscriptionResponse->successful())->toBeTrue();
    });
});
