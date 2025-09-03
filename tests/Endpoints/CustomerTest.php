<?php

use BoldlineStudios\RevenueCatApi\Data\AppData;
use BoldlineStudios\RevenueCatApi\Data\Customer\ActiveEntitlementData;
use BoldlineStudios\RevenueCatApi\Data\Customer\VirtualCurrencyBalanceData;
use BoldlineStudios\RevenueCatApi\Data\CustomerData;
use BoldlineStudios\RevenueCatApi\Data\EntitlementData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\ProductData;
use BoldlineStudios\RevenueCatApi\Data\PurchaseData;
use BoldlineStudios\RevenueCatApi\Data\SubscriptionData;
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

test('list returns ListPage of CustomerData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers?limit=10' => Http::response([
            'object' => 'list',
            'items' => [
                ['id' => 'customer1'],
                ['id' => 'customer2'],
            ],
            'next_page' => null,
            'url' => '/v2/projects/test_project/customers',
        ], 200),
    ]);

    $list = RevenueCat::customers()->all(10);

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
});

test('create returns CustomerData DTO', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers' => Http::response([
            'object' => 'customer',
            'id' => 'new_customer_id',
        ], 201),
    ]);

    $customer = RevenueCat::customers()->create('new_customer_id', [
        ['name' => '$email', 'value' => 'test@example.com'],
    ]);

    expect($customer)->toBeInstanceOf(CustomerData::class);
    expect($customer->getId())->toBe('new_customer_id');
});

test('get returns CustomerData with encoded customer id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id' => Http::response([
            'object' => 'customer',
            'id' => 'test-customer-id',
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $customer = RevenueCat::customers()->get($customerId);

    expect($customer)->toBeInstanceOf(CustomerData::class);
    expect($customer->getId())->toBe('test-customer-id');
});

test('delete returns true when deletion succeeds', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id' => Http::response([
            'object' => 'customer',
            'id' => 'test-customer-id',
            'deleted_at' => 1658399423658,
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $deleted = RevenueCat::customers()->delete($customerId);

    expect($deleted)->toBeTrue();
});

test('listOfSubscriptions returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/subscriptions' => Http::response([
            'object' => 'list',
            'items' => [
                [
                    'object' => 'subscription',
                    'id' => 'sub1',
                    'customer_id' => 'test-customer-id',
                    'original_customer_id' => 'original-customer-id',
                    'product_id' => 'prod1',
                    'purchased_at' => 1658399423658,
                    'total_revenue_in_usd' => [
                        'gross' => 9.99,
                        'commission' => 2.99,
                        'tax' => 0.75,
                        'proceeds' => 6.25,
                    ],
                    'current_period_starts_at' => 1658399423658,
                    'current_period_ends_at' => 1658399423658,
                    'gives_access' => true,
                    'pending_payment' => true,
                    'auto_renewal_status' => 'will_renew',
                    'status' => 'trialing',
                    'presented_offering_id' => 'ofrnge1a2b3c4d5',
                    'entitlements' => [
                        'object' => 'list',
                        'items' => [
                            'object' => 'entitlement',
                            'project_id' => 'proj1ab2c3d4',
                            'id' => 'entla1b2c3d4e5',
                            'lookup_key' => 'premium',
                            'display_name' => 'Premium',
                            'created_at' => 1658399423658,
                            'products' => [],
                        ],
                        'next_page' => '/v2/projects/proj1ab2c3d4/purchases/sub1a2b3c4d5e/entitlements?status=active&starting_after=entlab21dac',
                        'url' => '/v2/projects/proj1ab2c3d4/purchases/sub1a2b3c4d5e/entitlements',
                    ],
                    'environment' => 'production',
                    'store' => 'amazon',
                    'store_subscription_identifier' => 12345678,
                    'ownership' => 'purchased',
                    'country' => 'US',
                ],
                [
                    'object' => 'subscription',
                    'id' => 'sub2',
                    'customer_id' => 'test-customer-id',
                    'original_customer_id' => 'original-customer-id',
                    'product_id' => 'prod2',
                    'purchased_at' => 1658399423658,
                    'total_revenue_in_usd' => [
                        'gross' => 9.99,
                        'commission' => 2.99,
                        'tax' => 0.75,
                        'proceeds' => 6.25,
                    ],
                    'current_period_starts_at' => 1658399423658,
                    'current_period_ends_at' => 1658399423658,
                    'gives_access' => true,
                    'pending_payment' => true,
                    'auto_renewal_status' => 'will_renew',
                    'status' => 'trialing',
                    'total_revenue_in_usd' => [
                        'gross' => 9.99,
                        'commission' => 2.99,
                        'tax' => 0.75,
                        'proceeds' => 6.25,
                    ],
                    'presented_offering_id' => 'ofrnge1a2b3c4d5',
                    'entitlements' => [
                        'object' => 'list',
                        'items' => [
                            'object' => 'entitlement',
                            'project_id' => 'proj1ab2c3d4',
                            'id' => 'entla1b2c3d4e5',
                            'lookup_key' => 'premium',
                            'display_name' => 'Premium',
                            'created_at' => 1658399423658,
                            'products' => [],
                        ],
                        'next_page' => '/v2/projects/proj1ab2c3d4/purchases/sub1a2b3c4d5e/entitlements?status=active&starting_after=entlab21dac',
                        'url' => '/v2/projects/proj1ab2c3d4/purchases/sub1a2b3c4d5e/entitlements',
                    ],
                    'environment' => 'production',
                    'store' => 'amazon',
                    'store_subscription_identifier' => 12345678,
                    'ownership' => 'purchased',
                    'country' => 'US',
                ],
                'next_page' => '/v2/projects/proj1ab2c3d4/purchases/sub1a2b3c4d5e/entitlements?status=active&starting_after=entlab21dac',
                'url' => '/v2/projects/proj1ab2c3d4/purchases/sub1a2b3c4d5e/entitlements',
            ],

        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $listOfSubscriptions = RevenueCat::customers()->listOfSubscriptions($customerId);

    expect($listOfSubscriptions)->toBeInstanceOf(ListPage::class);
    expect(count($listOfSubscriptions->items()))->toBe(2);
    expect($listOfSubscriptions->items()[0])->toBeInstanceOf(SubscriptionData::class);
    expect($listOfSubscriptions->items()[0]->getId())->toBe('sub1');
    expect($listOfSubscriptions->items()[0]->getCustomerId())->toBe('test-customer-id');
    expect($listOfSubscriptions->items()[0]->getOriginalCustomerId())->toBe('original-customer-id');
    expect($listOfSubscriptions->items()[0]->getProductId())->toBe('prod1');
    expect($listOfSubscriptions->items()[0]->getTotalRevenueInUsd())->toBe(['gross' => 9.99, 'commission' => 2.99, 'tax' => 0.75, 'proceeds' => 6.25]);
    expect($listOfSubscriptions->items()[0]->getCurrentPeriodStartsAtMs())->toBe(1658399423658);
    expect($listOfSubscriptions->items()[0]->getCurrentPeriodEndsAtMs())->toBe(1658399423658);
    expect($listOfSubscriptions->items()[0]->getGivesAccess())->toBeTrue();
    expect($listOfSubscriptions->items()[1]->getId())->toBe('sub2');
    expect($listOfSubscriptions->items()[1]->getCustomerId())->toBe('test-customer-id');
    expect($listOfSubscriptions->items()[1]->getOriginalCustomerId())->toBe('original-customer-id');
    expect($listOfSubscriptions->items()[1]->getProductId())->toBe('prod2');
    expect($listOfSubscriptions->items()[1]->getTotalRevenueInUsd())->toBe(['gross' => 9.99, 'commission' => 2.99, 'tax' => 0.75, 'proceeds' => 6.25]);
    expect($listOfSubscriptions->items()[1]->getCurrentPeriodStartsAtMs())->toBe(1658399423658);
    expect($listOfSubscriptions->items()[1]->getCurrentPeriodEndsAtMs())->toBe(1658399423658);
    expect($listOfSubscriptions->items()[0]->getPendingPayment())->toBeTrue();
    expect($listOfSubscriptions->items()[0]->getAutoRenewalStatus())->toBe('will_renew');
    expect($listOfSubscriptions->items()[0]->getStatus())->toBe('trialing');
    expect($listOfSubscriptions->items()[0]->getPresentedOfferingId())->toBe('ofrnge1a2b3c4d5');
});

test('listOfPurchases returns ListPage of PurchaseData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/purchases' => Http::response([
            'object' => 'list',
            'items' => [
                [
                    'object' => 'purchase',
                    'id' => 'purchase1',
                    'customer_id' => 'test-customer-id',
                    'original_customer_id' => 'original-customer-id',
                    'product_id' => 'prod1',
                    'purchased_at' => 1658399423658,
                    'revenue_in_usd' => [
                        'currency' => 'USD',
                        'gross' => 9.99,
                        'commission' => 2.99,
                        'tax' => 0.75,
                        'proceeds' => 6.25,
                    ],
                    'current_period_starts_at' => 1658399423658,
                    'current_period_ends_at' => 1658399423658,
                    'gives_access' => true,
                    'pending_payment' => true,
                    'auto_renewal_status' => 'will_renew',
                    'status' => 'trialing',
                    'presented_offering_id' => 'ofrnge1a2b3c4d5',
                    'entitlements' => [
                        'object' => 'list',
                        'items' => [
                            [
                                'object' => 'entitlement',
                                'project_id' => 'proj1ab2c3d4',
                                'id' => 'entitlement1',
                                'lookup_key' => 'premium',
                                'display_name' => 'Premium',
                                'created_at' => 1658399423658,
                                'products' => [
                                    'object' => 'list',
                                    'items' => [
                                        [
                                            'object' => 'product',
                                            'id' => 'prod1',
                                            'store_identifier' => 'sku1',
                                            'type' => 'subscription',
                                            'subscription' => [],
                                            'one_time' => [],
                                            'created_at' => 1658399423658,
                                            'app_id' => 'app1',
                                            'app' => [
                                                'object' => 'app',
                                                'id' => 'app1',
                                                'name' => 'App 1',
                                                'type' => 'app_store',
                                                'created_at' => 1658399423658,
                                            ],
                                            'display_name' => 'Product 1',
                                        ],
                                    ],
                                    'next_page' => '/v2/projects/proj1ab2c3d4/entitlements/entitlement1/products?starting_after=prod1',
                                    'url' => '/v2/projects/proj1ab2c3d4/entitlements/entitlement1/products',
                                ],
                            ],
                            [
                                'object' => 'entitlement',
                                'project_id' => 'proj1ab2c3d4',
                                'id' => 'entitlement2',
                                'lookup_key' => 'pro',
                                'display_name' => 'Pro',
                                'created_at' => 1658399423658,
                                'products' => [
                                    'object' => 'list',
                                    'items' => [
                                        [
                                            'object' => 'product',
                                            'id' => 'prod2',
                                            'store_identifier' => 'sku1',
                                            'type' => 'subscription',
                                            'subscription' => [],
                                            'one_time' => [],
                                            'created_at' => 1658399423658,
                                            'app_id' => 'app1',
                                            'app' => [
                                                'object' => 'app',
                                                'id' => 'app1',
                                                'name' => 'App 1',
                                                'type' => 'app_store',
                                                'created_at' => 1658399423658,
                                            ],
                                            'display_name' => 'Product 2',
                                        ],
                                    ],
                                    'next_page' => '/v2/projects/proj1ab2c3d4/entitlements/entitlement2/products?starting_after=prod2',
                                    'url' => '/v2/projects/proj1ab2c3d4/entitlements/entitlement2/products',
                                ],
                            ],
                        ],
                        'next_page' => '/v2/projects/proj1ab2c3d4/purchases/sub1a2b3c4d5e/entitlements?status=active&starting_after=entlab21dac',
                        'url' => '/v2/projects/proj1ab2c3d4/purchases/sub1a2b3c4d5e/entitlements',
                    ],
                    'environment' => 'production',
                    'store' => 'amazon',
                    'store_subscription_identifier' => 12345678,
                    'ownership' => 'purchased',
                    'country' => 'US',
                ],
            ],
            'next_page' => '/v2/projects/proj1ab2c3d4/purchases/sub1a2b3c4d5e/purchases?status=active&starting_after=entlab21dac',
            'url' => '/v2/projects/proj1ab2c3d4/purchases/sub1a2b3c4d5e/purchases',
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $listOfPurchases = RevenueCat::customers()->listOfPurchases($customerId);

    expect($listOfPurchases)->toBeInstanceOf(ListPage::class);
    expect(count($listOfPurchases->items()))->toBe(1);
    expect($listOfPurchases->items()[0])->toBeInstanceOf(PurchaseData::class);
    expect($listOfPurchases->items()[0]->getId())->toBe('purchase1');
    expect($listOfPurchases->items()[0]->getCustomerId())->toBe('test-customer-id');
    expect($listOfPurchases->items()[0]->getOriginalCustomerId())->toBe('original-customer-id');
    expect($listOfPurchases->items()[0]->getProductId())->toBe('prod1');
    expect($listOfPurchases->items()[0]->getPurchasedAtMs())->toBe(1658399423658);
    expect($listOfPurchases->items()[0]->getRevenueInUsd())->toBe(['currency' => 'USD', 'gross' => 9.99, 'commission' => 2.99, 'tax' => 0.75, 'proceeds' => 6.25]);
    // test entitlements
    expect($listOfPurchases->items()[0]->getEntitlements())->toBeArray();
    expect($listOfPurchases->items()[0]->getEntitlements()[0])->toBeInstanceOf(EntitlementData::class);
    expect($listOfPurchases->items()[0]->getEntitlements()[0]->getId())->toBe('entitlement1');
    expect($listOfPurchases->items()[0]->getEntitlements()[0]->getLookupKey())->toBe('premium');
    expect($listOfPurchases->items()[0]->getEntitlements()[0]->getDisplayName())->toBe('Premium');
    // test second entitlement
    expect($listOfPurchases->items()[0]->getEntitlements()[1]->getId())->toBe('entitlement2');
    expect($listOfPurchases->items()[0]->getEntitlements()[1]->getLookupKey())->toBe('pro');
    expect($listOfPurchases->items()[0]->getEntitlements()[1]->getDisplayName())->toBe('Pro');
    expect($listOfPurchases->items()[0]->getEntitlements()[1]->getCreatedAtMs())->toBe(1658399423658);

    // test app inside of entitlements/products
    expect($listOfPurchases->items()[0]->getEntitlements()[0]->getProducts())->toBeArray();
    expect($listOfPurchases->items()[0]->getEntitlements()[0]->getProducts()[0])->toBeInstanceOf(ProductData::class);
    expect($listOfPurchases->items()[0]->getEntitlements()[0]->getProducts()[0]->getApp())->toBeInstanceOf(AppData::class);
    expect($listOfPurchases->items()[0]->getEntitlements()[0]->getProducts()[0]->getApp()->getId())->toBe('app1');
    expect($listOfPurchases->items()[0]->getEntitlements()[0]->getProducts()[0]->getApp()->getName())->toBe('App 1');
    expect($listOfPurchases->items()[0]->getEntitlements()[0]->getProducts()[0]->getApp()->getType())->toBe('app_store');
    expect($listOfPurchases->items()[0]->getEntitlements()[0]->getProducts()[0]->getApp()->getCreatedAtMs())->toBe(1658399423658);
});

test('listOfActiveEntitlements returns ListPage of CustomerActiveEntitlementData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/active_entitlements' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'customer.active_entitlement', 'entitlement_id' => 'ent1', 'expires_at' => 1658399423658],
                ['object' => 'customer.active_entitlement', 'entitlement_id' => 'ent2', 'expires_at' => 1658399423659],
            ],
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $list = RevenueCat::customers()->listOfActiveEntitlements($customerId);

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
    expect($list->items()[0])->toBeInstanceOf(ActiveEntitlementData::class);
    expect($list->items()[0]->getResourceType())->toBe('customer.active_entitlement');
    expect($list->items()[0]->getEntitlementId())->toBe('ent1');
    expect($list->items()[0]->getExpiresAtMs())->toBe(1658399423658);
    expect($list->items()[1])->toBeInstanceOf(ActiveEntitlementData::class);
    expect($list->items()[1]->getEntitlementId())->toBe('ent2');
    expect($list->items()[1]->getExpiresAtMs())->toBe(1658399423659);
});

test('listOfAliases returns ListPage of CustomerAliasData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/aliases' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'customer.alias', 'id' => '19b8de26-77c1-49f1-aa18-019a391603e2', 'created_at' => 1658399423658],
                ['object' => 'customer.alias', 'id' => '19b8de26-77c1-49f1-aa18-019a391603e3', 'created_at' => 1658399423659],
            ],
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $list = RevenueCat::customers()->listOfAliases($customerId);

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
});

test('listOfVirtualCurrencyBalances returns ListPage of CustomerVirtualCurrencyBalanceData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/virtual_currencies' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'virtual_currency_balance', 'currency_code' => 'string', 'balance' => 0],
                ['object' => 'virtual_currency_balance', 'currency_code' => 'string', 'balance' => 2],
            ],
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $list = RevenueCat::customers()->listOfVirtualCurrencyBalances($customerId);

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
    expect($list->items()[0])->toBeInstanceOf(VirtualCurrencyBalanceData::class);
    expect($list->items()[1])->toBeInstanceOf(VirtualCurrencyBalanceData::class);
    expect($list->items()[0]->getCurrencyCode())->toBe('string');
    expect($list->items()[0]->getBalance())->toBe(0);
    expect($list->items()[1]->getCurrencyCode())->toBe('string');
    expect($list->items()[1]->getBalance())->toBe(2);
});

test('listOfAttributes returns ListPage of CustomerAttributeData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/attributes' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'customer.attribute', 'name' => '$email', 'value' => 'example@example.com', 'updated_at' => 1658399423658],
                ['object' => 'customer.attribute', 'name' => '$email', 'value' => 'example2@example.com', 'updated_at' => 1658399423659],
            ],
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $list = RevenueCat::customers()->listOfAttributes($customerId);

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
});

test('setAttributes posts attributes and returns ListPage of Customer AttributeData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/attributes' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'customer.attribute', 'name' => '$email', 'value' => 'support@revenuecat.com', 'updated_at' => 1658399423658],
                ['object' => 'customer.attribute', 'name' => 'my_custom_attr', 'value' => 'custom value', 'updated_at' => 1658399423659],
            ],
            'next_page' => null,
            'url' => '/v2/projects/test_project/customers/test-customer-id/attributes',
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $attrs = [
        ['name' => '$email', 'value' => 'support@revenuecat.com'],
        ['name' => 'my_custom_attr', 'value' => 'custom value'],
    ];

    $list = RevenueCat::customers()->setAttributes($customerId, $attrs);

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
    expect($list->items()[0]->getName())->toBe('$email');
    expect($list->items()[0]->getValue())->toBe('support@revenuecat.com');
});

test('get method properly encodes special characters in customer id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test%20customer%20with%20spaces%20%26%20special%20chars' => Http::response([
            'object' => 'customer',
            'id' => 'test customer with spaces & special chars',
        ], 200),
    ]);

    $customerId = 'test customer with spaces & special chars';
    $customer = RevenueCat::customers()->get($customerId);

    expect($customer)->toBeInstanceOf(CustomerData::class);
    expect($customer->getId())->toBe('test customer with spaces & special chars');
});

test('list method works with empty query array', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers' => Http::response([
            'object' => 'list',
            'items' => [],
            'next_page' => null,
            'url' => '/v2/projects/test_project/customers',
        ], 200),
    ]);

    $list = RevenueCat::customers()->all();

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(0);
});

test('create throws if attributes is not a list', function () {
    expect(fn () => RevenueCat::customers()->create('bad_id', [
        'name' => '$email',
        'value' => 'test@example.com',
    ]))->toThrow(\InvalidArgumentException::class, 'Attributes must be a list of {name, value} items.');
});
