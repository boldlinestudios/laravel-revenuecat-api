<?php

use BoldlineStudios\RevenueCatApi\Data\EntitlementData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\SubscriptionData;
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

test('getSubscriptionEntitlements calls subscriptions()->listOfEntitlements() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/subscriptions/test-subscription-id/entitlements' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'entitlement', 'project_id' => 'test_project', 'id' => 'ent1', 'lookup_key' => 'premium', 'created_at' => 1658399423658, 'products' => []],
                ['object' => 'entitlement', 'project_id' => 'test_project', 'id' => 'ent2', 'lookup_key' => 'basic', 'created_at' => 1658399423659, 'products' => []],
            ],
        ], 200),
    ]);

    $list = RevenueCat::getSubscriptionEntitlements('test-subscription-id');

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
    expect($list->items()[0])->toBeInstanceOf(EntitlementData::class);
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
