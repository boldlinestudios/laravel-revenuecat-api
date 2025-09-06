<?php

use BoldlineStudios\RevenueCatApi\Data\Customer\ActiveEntitlementData;
use BoldlineStudios\RevenueCatApi\Data\Customer\AliasData;
use BoldlineStudios\RevenueCatApi\Data\Customer\AttributeData;
use BoldlineStudios\RevenueCatApi\Data\Customer\VirtualCurrencyBalanceData;
use BoldlineStudios\RevenueCatApi\Data\CustomerData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\PurchaseData;
use BoldlineStudios\RevenueCatApi\Data\SubscriptionData;
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

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

test('listCustomers calls customers()->all() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers?limit=10' => Http::response([
            'object' => 'list',
            'items' => [
                ['id' => 'customer1'],
                ['id' => 'customer2'],
            ],
        ], 200),
    ]);

    $list = RevenueCat::listCustomers(10);

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

test('listCustomerSubscriptions calls customers()->listOfSubscriptions() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/subscriptions?limit=10' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'subscription', 'id' => 'sub1', 'status' => 'active'],
            ],
        ], 200),
    ]);

    $listOfSubscriptions = RevenueCat::listCustomerSubscriptions('test-customer-id', 10);

    expect($listOfSubscriptions)->toBeInstanceOf(ListPage::class);
    expect(count($listOfSubscriptions->items()))->toBe(1);
    expect($listOfSubscriptions->items()[0])->toBeInstanceOf(SubscriptionData::class);
    expect($listOfSubscriptions->items()[0]->getId())->toBe('sub1');
    expect($listOfSubscriptions->items()[0]->getStatus())->toBe('active');
});

test('listCustomerPurchases calls customers()->listOfPurchases() with correct parameters', function () {
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
                                ],
                            ],
                        ],
                    ],
                    'environment' => 'production',
                    'store' => 'amazon',
                    'store_subscription_identifier' => 12345678,
                    'ownership' => 'purchased',
                    'country' => 'US',
                ],
            ],
        ], 200),
    ]);

    $response = RevenueCat::listCustomerPurchases('test-customer-id');

    expect($response)->toBeInstanceOf(ListPage::class);
    expect(count($response->items()))->toBe(1);
    expect($response->items()[0])->toBeInstanceOf(PurchaseData::class);
});

test('listCustomerActiveEntitlements calls customers()->listOfActiveEntitlements() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/active_entitlements' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'customer.active_entitlement', 'entitlement_id' => 'ent1', 'expires_at' => 1658399423658],
            ],
        ], 200),
    ]);

    $list = RevenueCat::listCustomerActiveEntitlements('test-customer-id');

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(1);
    expect($list->items()[0])->toBeInstanceOf(ActiveEntitlementData::class);
});

test('listCustomerAliases calls customers()->listOfAliases() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/aliases' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'customer.alias', 'id' => '19b8de26-77c1-49f1-aa18-019a391603e2', 'created_at' => 1658399423658],
            ],
        ], 200),
    ]);

    $list = RevenueCat::listCustomerAliases('test-customer-id');

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(1);
    expect($list->items()[0])->toBeInstanceOf(AliasData::class);
});

test('listCustomerVirtualCurrencyBalances calls customers()->listOfVirtualCurrencyBalances() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/virtual_currencies' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'virtual_currency_balance', 'currency_code' => 'coins', 'balance' => 100],
                ['object' => 'virtual_currency_balance', 'currency_code' => 'gems', 'balance' => 200],
            ],
        ], 200),
    ]);

    $list = RevenueCat::listCustomerVirtualCurrencyBalances('test-customer-id');

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
    expect($list->items()[0])->toBeInstanceOf(VirtualCurrencyBalanceData::class);
});

test('listCustomerAttributes calls customers()->listOfAttributes() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/attributes' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'customer.attribute', 'name' => '$email', 'value' => 'example@example.com', 'updated_at' => 1658399423658],
                ['object' => 'customer.attribute', 'name' => '$email', 'value' => 'example2@example.com', 'updated_at' => 1658399423659],
            ],
        ], 200),
    ]);

    $list = RevenueCat::listCustomerAttributes('test-customer-id');

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
    expect($list->items()[0])->toBeInstanceOf(AttributeData::class);
});

test('setCustomerAttributes calls customers()->setAttributes() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/attributes' => Http::response([
            'object' => 'customer',
            'id' => 'test-customer-id',
        ], 200),
    ]);

    $attrs = [
        ['name' => '$email', 'value' => 'support@revenuecat.com'],
    ];

    $customer = RevenueCat::setCustomerAttributes('test-customer-id', $attrs);

    expect($customer)->toBeInstanceOf(CustomerData::class);
    expect($customer->getId())->toBe('test-customer-id');
});

test('setAttributes posts attributes and returns CustomerData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/attributes' => Http::response([
            'object' => 'customer',
            'id' => 'test-customer-id',
        ], 200),
    ]);

    $attrs = [
        ['name' => '$email', 'value' => 'support@revenuecat.com'],
    ];

    $customer = RevenueCat::customers()->setAttributes('test-customer-id', $attrs);

    expect($customer)->toBeInstanceOf(CustomerData::class);
    expect($customer->getId())->toBe('test-customer-id');
});
