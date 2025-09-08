<?php

use BoldlineStudios\RevenueCatApi\Data\EntitlementData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\SubscriptionData;
use BoldlineStudios\RevenueCatApi\Data\Subscriptions\ManagementUrlData;
use BoldlineStudios\RevenueCatApi\Data\Subscriptions\TransactionData;
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

test('get returns response from client with encoded subscription id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id' => Http::response([
            'object' => 'subscription',
            'id' => 'test-subscription-id',
            'customer_id' => 'customer123',
            'original_customer_id' => 'original_customer123',
            'product_id' => 'product123',
            'status' => 'active',
            'total_revenue_in_usd' => [
                'currency' => 'USD',
                'gross' => 100,
                'commission' => 10,
                'tax' => 0.75,
                'proceeds' => 90,
            ],
            'entitlements' => [
                'premium_access' => true,
                'basic_access' => false,
            ],
            'starts_at' => 1714435200000,
            'current_period_starts_at' => 1714435200000,
            'gives_access' => true,
            'pending_payment' => false,
            'auto_renewal_status' => 'active',
            'environment' => 'production',
            'store' => 'app_store',
            'store_subscription_identifier' => '1234567890',
            'ownership' => 'purchased',
            'country' => 'US',
        ], 200),
    ]);

    $subscriptionId = 'test-subscription-id';
    $subscription = RevenueCat::subscriptions()->get($subscriptionId);

    expect($subscription)->toBeInstanceOf(SubscriptionData::class);
    expect($subscription->getId())->toBe('test-subscription-id');
});

test('listOfEntitlements returns ListPage of EntitlementData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/entitlements' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'entitlement', 'project_id' => 'test_project', 'id' => 'entitlement1', 'lookup_key' => 'premium', 'display_name' => 'Premium', 'created_at' => 1658399423658, 'products' => []],
                ['object' => 'entitlement', 'project_id' => 'test_project', 'id' => 'entitlement2', 'lookup_key' => 'basic', 'display_name' => 'Basic', 'created_at' => 1658399423659, 'products' => []],
            ],
        ], 200),
    ]);

    $subscriptionId = 'test-subscription-id';
    $response = RevenueCat::subscriptions()->listOfEntitlements($subscriptionId);

    expect($response)->toBeInstanceOf(ListPage::class);
    expect(count($response->items()))->toBe(2);
    expect($response->items()[0])->toBeInstanceOf(EntitlementData::class);
    expect($response->items()[0]->getId())->toBe('entitlement1');
    expect($response->items()[0]->getProjectId())->toBe('test_project');
    expect($response->items()[0]->getLookupKey())->toBe('premium');
    expect($response->items()[0]->getDisplayName())->toBe('Premium');
    expect($response->items()[0]->getCreatedAtMs())->toBe(1658399423658);
    expect($response->items()[0]->getProducts())->toBe([]);
    expect($response->items()[1])->toBeInstanceOf(EntitlementData::class);
    expect($response->items()[1]->getId())->toBe('entitlement2');
});

test('listOfTransactions returns ListPage of TransactionData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/transactions' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'subscription_transaction', 'id' => 'transaction1', 'purchased_at' => 1658399423658],
                ['object' => 'subscription_transaction', 'id' => 'transaction2', 'purchased_at' => 1658399423659],
            ],
            'next_page' => null,
            'url' => '/v2/projects/test_project/subscriptions/test-subscription-id/transactions',
        ], 200),
    ]);

    $subscriptionId = 'test-subscription-id';
    $listPage = RevenueCat::subscriptions()->listOfTransactions($subscriptionId);

    expect($listPage)->toBeInstanceOf(ListPage::class);
    expect(count($listPage->items()))->toBe(2);
    expect($listPage->items()[0])->toBeInstanceOf(TransactionData::class);
    expect($listPage->items()[0]->getId())->toBe('transaction1');
    expect($listPage->items()[0]->getPurchasedAtMs())->toBe(1658399423658);
    expect($listPage->items()[1])->toBeInstanceOf(TransactionData::class);
    expect($listPage->items()[1]->getId())->toBe('transaction2');
    expect($listPage->items()[1]->getPurchasedAtMs())->toBe(1658399423659);
});

test('getCustomerPortalUrl returns ManagementUrlData from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/authenticated_management_url' => Http::response([
            'object' => 'authenticated_management_url',
            'management_url' => 'https://portal.example.com/access/abc123',
        ], 200),
    ]);

    $subscriptionId = 'test-subscription-id';
    $urlData = RevenueCat::subscriptions()->getCustomerPortalUrl($subscriptionId);

    expect($urlData->getManagementUrl())->toBe('https://portal.example.com/access/abc123');
    expect($urlData->getResourceType())->toBe('authenticated_management_url');
    expect($urlData->getRaw())->toBe([
        'object' => 'authenticated_management_url',
        'management_url' => 'https://portal.example.com/access/abc123',
    ]);
    expect($urlData->toArray())->toBe([
        'object' => 'authenticated_management_url',
        'management_url' => 'https://portal.example.com/access/abc123',
        'raw' => [
            'object' => 'authenticated_management_url',
            'management_url' => 'https://portal.example.com/access/abc123',
        ],
    ]);
});

test('cancelWebBillingSubscription returns SubscriptionData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/actions/cancel' => Http::response([
            'object' => 'subscription',
            'id' => 'test-subscription-id',
            'customer_id' => 'customer123',
            'original_customer_id' => 'original_customer123',
            'product_id' => 'product123',
            'status' => 'cancelled',
            'total_revenue_in_usd' => [
                'currency' => 'USD',
                'gross' => 100,
                'commission' => 10,
                'tax' => 0.75,
                'proceeds' => 90,
            ],
            'entitlements' => [
                'premium_access' => true,
                'basic_access' => false,
            ],
            'starts_at' => 1714435200000,
            'current_period_starts_at' => 1714435200000,
            'gives_access' => true,
            'pending_payment' => false,
            'auto_renewal_status' => 'active',
            'environment' => 'production',
            'store' => 'app_store',
            'store_subscription_identifier' => '1234567890',
            'ownership' => 'purchased',
            'country' => 'US',
        ], 200),
    ]);

    $subscriptionId = 'test-subscription-id';
    $subscription = RevenueCat::subscriptions()->cancelWebBillingSubscription($subscriptionId);

    expect($subscription)->toBeInstanceOf(SubscriptionData::class);
    expect($subscription->getId())->toBe('test-subscription-id');
    expect($subscription->getStatus())->toBe('cancelled');
    expect($subscription->getCustomerId())->toBe('customer123');
    expect($subscription->getProductId())->toBe('product123');
});

test('refundWebBillingSubscription returns SubscriptionData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/actions/refund' => Http::response([
            'object' => 'subscription',
            'id' => 'test-subscription-id',
            'customer_id' => 'customer123',
            'original_customer_id' => 'original_customer123',
            'product_id' => 'product123',
            'status' => 'refunded',
            'total_revenue_in_usd' => [
                'currency' => 'USD',
                'gross' => 100,
                'commission' => 10,
                'tax' => 0.75,
                'proceeds' => 90,
            ],
            'entitlements' => [
                'premium_access' => true,
                'basic_access' => false,
            ],
            'starts_at' => 1714435200000,
            'current_period_starts_at' => 1714435200000,
            'gives_access' => true,
            'pending_payment' => false,
            'auto_renewal_status' => 'active',
            'environment' => 'production',
            'store' => 'app_store',
            'store_subscription_identifier' => '1234567890',
            'ownership' => 'purchased',
            'country' => 'US',
        ], 200),
    ]);

    $subscriptionId = 'test-subscription-id';
    $subscription = RevenueCat::subscriptions()->refundWebBillingSubscription($subscriptionId);

    expect($subscription)->toBeInstanceOf(SubscriptionData::class);
    expect($subscription->getId())->toBe('test-subscription-id');
    expect($subscription->getStatus())->toBe('refunded');
    expect($subscription->getCustomerId())->toBe('customer123');
    expect($subscription->getProductId())->toBe('product123');
});

test('get method properly encodes special characters in subscription id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars' => Http::response([
            'object' => 'subscription',
            'id' => 'test subscription with spaces & special chars',
            'customer_id' => 'customer123',
            'original_customer_id' => 'original_customer123',
            'product_id' => 'special_product',
            'status' => 'active',
            'total_revenue_in_usd' => [
                'currency' => 'USD',
                'gross' => 100,
                'commission' => 10,
                'tax' => 0.75,
                'proceeds' => 90,
            ],
            'entitlements' => [
                'object' => 'list',
                'items' => [
                    ['object' => 'entitlement', 'project_id' => 'test_project', 'id' => 'entitlement1', 'lookup_key' => 'premium', 'display_name' => 'Premium', 'created_at' => 1658399423658, 'products' => []],
                    ['object' => 'entitlement', 'project_id' => 'test_project', 'id' => 'entitlement2', 'lookup_key' => 'basic', 'display_name' => 'Basic', 'created_at' => 1658399423659, 'products' => []],
                ],
            ],
            'starts_at' => 1714435200000,
            'current_period_starts_at' => 1714435200000,
            'gives_access' => true,
            'pending_payment' => false,
            'auto_renewal_status' => 'active',
            'environment' => 'production',
            'store' => 'app_store',
            'store_subscription_identifier' => '1234567890',
            'ownership' => 'purchased',
            'country' => 'US',
        ], 200),
    ]);

    $subscriptionId = 'test subscription with spaces & special chars';
    $subscription = RevenueCat::subscriptions()->get($subscriptionId);

    expect($subscription)->toBeInstanceOf(SubscriptionData::class);
    expect($subscription->getId())->toBe('test subscription with spaces & special chars');
});

test('listOfEntitlements method properly encodes special characters in subscription id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars/entitlements' => Http::response([
            'object' => 'list',
            'items' => [],
        ], 200),
    ]);

    $subscriptionId = 'test subscription with spaces & special chars';
    $response = RevenueCat::subscriptions()->listOfEntitlements($subscriptionId);

    expect($response)->toBeInstanceOf(ListPage::class);
    expect(count($response->items()))->toBe(0);
    expect($response->items())->toBe([]);
    expect($response->nextCursor())->toBeNull();
});

test('listOfTransactions method properly encodes special characters in subscription id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars/transactions*' => Http::response([
            'object' => 'list',
            'items' => [],
            'next_page' => null,
            'url' => '/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars/transactions&limit=20',
        ], 200),
    ]);

    $subscriptionId = 'test subscription with spaces & special chars';
    $listPage = RevenueCat::subscriptions()->listOfTransactions($subscriptionId);

    expect($listPage)->toBeInstanceOf(ListPage::class);
    expect(count($listPage->items()))->toBe(0);
    expect($listPage->items())->toBe([]);
    expect($listPage->nextCursor())->toBeNull();
});

test('getCustomerPortalUrl method properly encodes special characters in subscription id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars/authenticated_management_url' => Http::response([
            'management_url' => 'https://portal.example.com/access/def456',
            'object' => 'authenticated_management_url',
        ], 200),
    ]);

    $subscriptionId = 'test subscription with spaces & special chars';
    $urlData = RevenueCat::subscriptions()->getCustomerPortalUrl($subscriptionId);

    expect($urlData)->toBeInstanceOf(ManagementUrlData::class);
    expect($urlData->getManagementUrl())->toBe('https://portal.example.com/access/def456');
    expect($urlData->getResourceType())->toBe('authenticated_management_url');
});

test('cancelWebBillingSubscription method properly encodes special characters in subscription id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars/actions/cancel' => Http::response([
            'object' => 'subscription',
            'id' => 'test subscription with spaces & special chars',
            'customer_id' => 'customer123',
            'original_customer_id' => 'original_customer123',
            'product_id' => 'product123',
            'status' => 'cancelled',
            'total_revenue_in_usd' => [
                'currency' => 'USD',
                'gross' => 100,
                'commission' => 10,
                'tax' => 0.75,
                'proceeds' => 90,
            ],
            'entitlements' => [
                'premium_access' => true,
                'basic_access' => false,
            ],
            'starts_at' => 1714435200000,
            'current_period_starts_at' => 1714435200000,
            'gives_access' => true,
            'pending_payment' => false,
            'auto_renewal_status' => 'active',
            'environment' => 'production',
            'store' => 'app_store',
            'store_subscription_identifier' => '1234567890',
            'ownership' => 'purchased',
            'country' => 'US',
        ], 200),
    ]);

    $subscriptionId = 'test subscription with spaces & special chars';
    $subscription = RevenueCat::subscriptions()->cancelWebBillingSubscription($subscriptionId);

    expect($subscription)->toBeInstanceOf(SubscriptionData::class);
    expect($subscription->getId())->toBe('test subscription with spaces & special chars');
    expect($subscription->getStatus())->toBe('cancelled');
});

test('refundWebBillingSubscription method properly encodes special characters in subscription id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars/actions/refund' => Http::response([
            'object' => 'subscription',
            'id' => 'test subscription with spaces & special chars',
            'customer_id' => 'customer123',
            'original_customer_id' => 'original_customer123',
            'product_id' => 'product123',
            'status' => 'refunded',
            'total_revenue_in_usd' => [
                'currency' => 'USD',
                'gross' => 100,
                'commission' => 10,
                'tax' => 0.75,
                'proceeds' => 90,
            ],
            'entitlements' => [
                'premium_access' => true,
                'basic_access' => false,
            ],
            'starts_at' => 1714435200000,
            'current_period_starts_at' => 1714435200000,
            'gives_access' => true,
            'pending_payment' => false,
            'auto_renewal_status' => 'active',
            'environment' => 'production',
            'store' => 'app_store',
            'store_subscription_identifier' => '1234567890',
            'ownership' => 'purchased',
            'country' => 'US',
        ], 200),
    ]);

    $subscriptionId = 'test subscription with spaces & special chars';
    $subscription = RevenueCat::subscriptions()->refundWebBillingSubscription($subscriptionId);

    expect($subscription)->toBeInstanceOf(SubscriptionData::class);
    expect($subscription->getId())->toBe('test subscription with spaces & special chars');
    expect($subscription->getStatus())->toBe('refunded');
});

test('refundPlayStoreSubscriptionTransaction returns TransactionData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/transactions/test-transaction-id/actions/refund' => Http::response([
            'object' => 'subscription_transaction',
            'id' => 'test-transaction-id',
            'purchased_at' => 1658399423658,
        ], 200),
    ]);

    $subscriptionId = 'test-subscription-id';
    $transactionId = 'test-transaction-id';
    $transaction = RevenueCat::subscriptions()->refundPlayStoreSubscriptionTransaction($subscriptionId, $transactionId);

    expect($transaction)->toBeInstanceOf(TransactionData::class);
    expect($transaction->getId())->toBe('test-transaction-id');
    expect($transaction->getPurchasedAtMs())->toBe(1658399423658);
});

test('refundPlayStoreSubscriptionTransaction method properly encodes special characters in subscription and transaction ids', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars/transactions/test%20transaction%20with%20spaces%20%26%20special%20chars/actions/refund' => Http::response([
            'object' => 'subscription_transaction',
            'id' => 'test transaction with spaces & special chars',
            'purchased_at' => 1658399423658,
        ], 200),
    ]);

    $subscriptionId = 'test subscription with spaces & special chars';
    $transactionId = 'test transaction with spaces & special chars';
    $transaction = RevenueCat::subscriptions()->refundPlayStoreSubscriptionTransaction($subscriptionId, $transactionId);

    expect($transaction)->toBeInstanceOf(TransactionData::class);
    expect($transaction->getId())->toBe('test transaction with spaces & special chars');
});
