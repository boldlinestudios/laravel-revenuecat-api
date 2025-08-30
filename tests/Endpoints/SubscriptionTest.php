<?php

use BoldlineStudios\RevenueCatApi\Data\EntitlementData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
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

// TODO: return ListPage<SubscriptionTransactionData>
test('listOfTransactions returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/transactions' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'subscription_transaction', 'id' => 'transaction1', 'purchased_at' => 1658399423658],
                ['object' => 'subscription_transaction', 'id' => 'transaction2', 'purchased_at' => 1658399423659],
            ],
        ], 200),
    ]);

    $subscriptionId = 'test-subscription-id';
    $response = RevenueCat::subscriptions()->listOfTransactions($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect(count($response->json('items')))->toBe(2);
});

test('getCustomerPortalUrl returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/authenticated_management_url' => Http::response([
            'object' => 'authenticated_management_url',
            'url' => 'https://portal.example.com/access/abc123',
            'expires_at' => '2024-01-01T01:00:00Z',
        ], 200),
    ]);

    $subscriptionId = 'test-subscription-id';
    $response = RevenueCat::subscriptions()->getCustomerPortalUrl($subscriptionId);

    expect($response->json('url'))->toBe('https://portal.example.com/access/abc123');
});

// TODO: return subscription data
test('cancelWebBillingSubscription returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/actions/cancel' => Http::response([
            'object' => 'subscription',
            'id' => 'test-subscription-id',
            'customer_id' => 'customer123',
            'original_customer_id' => 'original_customer123',
            'product_id' => 'product123',

            'status' => 'cancelled',
            'cancelled_at' => '2024-01-01T00:00:00Z',
        ], 200),
    ]);

    $subscriptionId = 'test-subscription-id';
    $response = RevenueCat::subscriptions()->cancelWebBillingSubscription($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('status'))->toBe('cancelled');
});

test('refundWebBillingSubscription returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/actions/refund' => Http::response([
            'id' => 'test-subscription-id',
            'status' => 'refunded',
            'refunded_at' => '2024-01-01T00:00:00Z',
        ], 200),
    ]);

    $subscriptionId = 'test-subscription-id';
    $response = RevenueCat::subscriptions()->refundWebBillingSubscription($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('status'))->toBe('refunded');
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

// TODO: return ListPage<SubscriptionTransactionData>
test('listOfTransactions method properly encodes special characters in subscription id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars/transactions' => Http::response([
            'object' => 'list',
            'items' => [],
            'next_page' => null,
            'url' => '/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars/transactions',
        ], 200),
    ]);

    $subscriptionId = 'test subscription with spaces & special chars';
    $response = RevenueCat::subscriptions()->listOfTransactions($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect(count($response->json('items')))->toBe(0);
});

test('getCustomerPortalUrl method properly encodes special characters in subscription id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars/authenticated_management_url' => Http::response([
            'url' => 'https://portal.example.com/access/def456',
        ], 200),
    ]);

    $subscriptionId = 'test subscription with spaces & special chars';
    $response = RevenueCat::subscriptions()->getCustomerPortalUrl($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('url'))->toBe('https://portal.example.com/access/def456');
});

test('cancelWebBillingSubscription method properly encodes special characters in subscription id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars/actions/cancel' => Http::response([
            'id' => 'test subscription with spaces & special chars',
            'status' => 'cancelled',
        ], 200),
    ]);

    $subscriptionId = 'test subscription with spaces & special chars';
    $response = RevenueCat::subscriptions()->cancelWebBillingSubscription($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('status'))->toBe('cancelled');
});

test('refundWebBillingSubscription method properly encodes special characters in subscription id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test%20subscription%20with%20spaces%20%26%20special%20chars/actions/refund' => Http::response([
            'id' => 'test subscription with spaces & special chars',
            'status' => 'refunded',
        ], 200),
    ]);

    $subscriptionId = 'test subscription with spaces & special chars';
    $response = RevenueCat::subscriptions()->refundWebBillingSubscription($subscriptionId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('status'))->toBe('refunded');
});
