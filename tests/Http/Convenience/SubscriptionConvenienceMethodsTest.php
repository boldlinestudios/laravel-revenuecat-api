<?php

use BoldlineStudios\RevenueCatApi\Data\EntitlementData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\SubscriptionData;
use BoldlineStudios\RevenueCatApi\Data\Subscriptions\TransactionData;
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

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

test('listSubscriptionEntitlements calls subscriptions()->listOfEntitlements() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/entitlements' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'entitlement', 'project_id' => 'test_project', 'id' => 'ent1', 'lookup_key' => 'premium', 'created_at' => 1658399423658, 'products' => []],
                ['object' => 'entitlement', 'project_id' => 'test_project', 'id' => 'ent2', 'lookup_key' => 'basic', 'created_at' => 1658399423659, 'products' => []],
            ],
        ], 200),
    ]);

    $list = RevenueCat::listSubscriptionEntitlements('test-subscription-id');

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
    expect($list->items()[0])->toBeInstanceOf(EntitlementData::class);
});

test('listSubscriptionTransactions calls subscriptions()->listOfTransactions() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/transactions' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'subscription_transaction', 'id' => 'txn1', 'purchased_at' => 1658399423658],
                ['object' => 'subscription_transaction', 'id' => 'txn2', 'purchased_at' => 1658399423659],
            ],
            'next_page' => null,
            'url' => '/v2/projects/test_project/subscriptions/test-subscription-id/transactions',
        ], 200),
    ]);

    $listPage = RevenueCat::listSubscriptionTransactions('test-subscription-id');

    expect($listPage)->toBeInstanceOf(ListPage::class);
    expect(count($listPage->items()))->toBe(2);
    expect($listPage->items()[0])->toBeInstanceOf(TransactionData::class);
    expect($listPage->items()[0]->getId())->toBe('txn1');
    expect($listPage->items()[0]->getPurchasedAtMs())->toBe(1658399423658);
    expect($listPage->items()[1])->toBeInstanceOf(TransactionData::class);
    expect($listPage->items()[1]->getId())->toBe('txn2');
    expect($listPage->items()[1]->getPurchasedAtMs())->toBe(1658399423659);
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

    $subscription = RevenueCat::cancelWebBillingSubscription('test-subscription-id');

    expect($subscription)->toBeInstanceOf(SubscriptionData::class);
    expect($subscription->getId())->toBe('test-subscription-id');
    expect($subscription->getStatus())->toBe('cancelled');
});

test('refundWebBillingSubscription calls subscriptions()->refundWebBillingSubscription() with correct parameters', function () {
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

    $subscription = RevenueCat::refundWebBillingSubscription('test-subscription-id');

    expect($subscription)->toBeInstanceOf(SubscriptionData::class);
    expect($subscription->getId())->toBe('test-subscription-id');
    expect($subscription->getStatus())->toBe('refunded');
});

test('refundPlayStoreSubscriptionTransaction calls subscriptions()->refundPlayStoreSubscriptionTransaction() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/transactions/test-transaction-id/actions/refund' => Http::response([
            'object' => 'subscription_transaction',
            'id' => 'test-transaction-id',
            'purchased_at' => 1658399423658,
        ], 200),
    ]);

    $transaction = RevenueCat::refundPlayStoreSubscriptionTransaction('test-subscription-id', 'test-transaction-id');

    expect($transaction)->toBeInstanceOf(TransactionData::class);
    expect($transaction->getId())->toBe('test-transaction-id');
    expect($transaction->getPurchasedAtMs())->toBe(1658399423658);
});
