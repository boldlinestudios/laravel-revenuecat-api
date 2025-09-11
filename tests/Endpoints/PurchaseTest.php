<?php

use BoldLineStudios\RevenueCatApi\Data\EntitlementData;
use BoldLineStudios\RevenueCatApi\Data\ListPage;
use BoldLineStudios\RevenueCatApi\Data\PurchaseData;
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

test('get returns response from client with encoded purchase id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/purchases/test-purchase-id' => Http::response([
            'object' => 'purchase',
            'id' => 'test-purchase-id',
            'product_id' => 'product123',
            'purchase_date' => '2024-01-01T00:00:00Z',
        ], 200),
    ]);

    $purchaseId = 'test-purchase-id';
    $purchase = RevenueCat::purchases()->get($purchaseId);

    expect($purchase)->toBeInstanceOf(PurchaseData::class);
    expect($purchase->getId())->toBe('test-purchase-id');
});

test('listOfEntitlements returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/purchases/test-purchase-id/entitlements' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'entitlement', 'id' => 'entitlement1', 'identifier' => 'premium_access'],
                ['object' => 'entitlement', 'id' => 'entitlement2', 'identifier' => 'basic_access'],
            ],
        ], 200),
    ]);

    $purchaseId = 'test-purchase-id';
    $response = RevenueCat::purchases()->listOfEntitlements($purchaseId);

    expect($response)->toBeInstanceOf(ListPage::class);
    expect(count($response->items()))->toBe(2);
    expect($response->items()[0])->toBeInstanceOf(EntitlementData::class);
    expect($response->items()[0]->getId())->toBe('entitlement1');
    expect($response->items()[1])->toBeInstanceOf(EntitlementData::class);
    expect($response->items()[1]->getId())->toBe('entitlement2');
});

test('get method properly encodes special characters in purchase id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/purchases/test%20purchase%20with%20spaces%20%26%20special%20chars' => Http::response([
            'object' => 'purchase',
            'id' => 'test purchase with spaces & special chars',
            'product_id' => 'special_product',
        ], 200),
    ]);

    $purchaseId = 'test purchase with spaces & special chars';
    $purchase = RevenueCat::purchases()->get($purchaseId);

    expect($purchase)->toBeInstanceOf(PurchaseData::class);
    expect($purchase->getId())->toBe('test purchase with spaces & special chars');
});

test('listOfEntitlements method properly encodes special characters in purchase id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/purchases/test%20purchase%20with%20spaces%20%26%20special%20chars/entitlements' => Http::response([
            'object' => 'list',
            'items' => [],
            'next_page' => null,
            'url' => '/v2/projects/test_project/purchases/test%20purchase%20with%20spaces%20%26%20special%20chars/entitlements',
        ], 200),
    ]);

    $purchaseId = 'test purchase with spaces & special chars';
    $response = RevenueCat::purchases()->listOfEntitlements($purchaseId);

    expect($response)->toBeInstanceOf(ListPage::class);
    expect(count($response->items()))->toBe(0);
    expect($response->items())->toBe([]);
    expect($response->nextCursor())->toBeNull();
});

test('refundWebBillingPurchase refunds purchase and returns PurchaseData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/purchases/test-purchase-id/actions/refund' => Http::response([
            'object' => 'purchase',
            'id' => 'test-purchase-id',
            'customer_id' => 'customer123',
            'product_id' => 'product123',
            'purchased_at' => 1658399423658,
            'refunded_at' => 1658399423659,
        ], 200),
    ]);

    $purchaseId = 'test-purchase-id';
    $purchase = RevenueCat::purchases()->refundWebBillingPurchase($purchaseId);

    expect($purchase)->toBeInstanceOf(PurchaseData::class);
    expect($purchase->getId())->toBe('test-purchase-id');
    expect($purchase->getCustomerId())->toBe('customer123');
    expect($purchase->getProductId())->toBe('product123');
});
