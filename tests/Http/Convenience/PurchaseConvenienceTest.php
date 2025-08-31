<?php

use BoldlineStudios\RevenueCatApi\Data\EntitlementData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\PurchaseData;
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

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
                ['object' => 'entitlement', 'project_id' => 'test_project', 'id' => 'ent1', 'lookup_key' => 'premium', 'created_at' => 1658399423658, 'products' => []],
                ['object' => 'entitlement', 'project_id' => 'test_project', 'id' => 'ent2', 'lookup_key' => 'basic', 'created_at' => 1658399423659, 'products' => []],
            ],
        ], 200),
    ]);

    $list = RevenueCat::getPurchaseEntitlements('test-purchase-id');

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
    expect($list->items()[0])->toBeInstanceOf(EntitlementData::class);
    expect($list->items()[0]->getId())->toBe('ent1');
    expect($list->items()[0]->getLookupKey())->toBe('premium');
    expect($list->items()[0]->getCreatedAtMs())->toBe(1658399423658);
    expect($list->items()[0]->getProducts())->toBe([]);
    expect($list->items()[1])->toBeInstanceOf(EntitlementData::class);
    expect($list->items()[1]->getId())->toBe('ent2');
    expect($list->items()[1]->getLookupKey())->toBe('basic');
    expect($list->items()[1]->getCreatedAtMs())->toBe(1658399423659);
    expect($list->items()[1]->getProducts())->toBe([]);
});
