<?php

use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\OfferingData;
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
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

test('list returns ListPage of OfferingData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings?limit=10' => Http::response([
            'object' => 'list',
            'items' => [
                ['id' => 'offering1', 'lookup_key' => 'default', 'display_name' => 'Default'],
                ['id' => 'offering2', 'lookup_key' => 'premium', 'display_name' => 'Premium'],
            ],
            'next_page' => null,
            'url' => '/v2/projects/test_project/offerings',
        ], 200),
    ]);

    $offerings = RevenueCat::offerings()->list(10);

    expect($offerings)->toBeInstanceOf(ListPage::class);
    expect(count($offerings->items()))->toBe(2);
});

test('create returns OfferingData DTO', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings' => Http::response([
            'object' => 'offering',
            'id' => 'new_offering_id',
            'lookup_key' => 'new_premium',
            'display_name' => 'New Premium',
            'created_at' => 1704067200000,
            'metadata' => ['color' => 'blue', 'call_to_action' => 'Get it now'],
        ], 201),
    ]);

    $offering = RevenueCat::offerings()->create(
        'new_premium',
        'New Premium',
        ['color' => 'blue', 'call_to_action' => 'Get it now']
    );

    expect($offering)->toBeInstanceOf(OfferingData::class);
    expect($offering->getId())->toBe('new_offering_id');
    expect($offering->getLookupKey())->toBe('new_premium');
    expect($offering->getDisplayName())->toBe('New Premium');
    expect($offering->getMetadata())->toBe(['color' => 'blue', 'call_to_action' => 'Get it now']);
});

test('get returns OfferingData with encoded offering id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings/test-offering-id' => Http::response([
            'object' => 'offering',
            'id' => 'test-offering-id',
            'lookup_key' => 'premium',
            'display_name' => 'Premium offering',
        ], 200),
    ]);

    $offeringId = 'test-offering-id';
    $offering = RevenueCat::offerings()->get($offeringId);

    expect($offering)->toBeInstanceOf(OfferingData::class);
    expect($offering->getId())->toBe('test-offering-id');
});

test('update returns OfferingData DTO', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings/test-offering-id' => Http::response([
            'object' => 'offering',
            'id' => 'test-offering-id',
            'lookup_key' => 'premium_plus',
            'display_name' => 'Updated premium offering',
        ], 200),
    ]);

    $offeringId = 'test-offering-id';
    $data = ['lookup_key' => 'premium_plus', 'display_name' => 'Updated premium offering'];
    $offering = RevenueCat::offerings()->update($offeringId, $data);

    expect($offering)->toBeInstanceOf(OfferingData::class);
    expect($offering->getDisplayName())->toBe('Updated premium offering');
});

test('delete returns true when deletion succeeds', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings/test-offering-id' => Http::response([
            'object' => 'offering',
            'id' => 'test-offering-id',
            'deleted_at' => 1658399423658,
        ], 200),
    ]);

    $offeringId = 'test-offering-id';
    $deleted = RevenueCat::offerings()->delete($offeringId);

    expect($deleted)->toBeTrue();
});

test('get method properly encodes special characters in offering id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings/test%20offering%20with%20spaces%20%26%20special%20chars' => Http::response([
            'object' => 'offering',
            'id' => 'test offering with spaces & special chars',
            'lookup_key' => 'special_offering',
            'display_name' => 'Special Offering',
        ], 200),
    ]);

    $offeringId = 'test offering with spaces & special chars';
    $offering = RevenueCat::offerings()->get($offeringId);

    expect($offering)->toBeInstanceOf(OfferingData::class);
    expect($offering->getId())->toBe('test offering with spaces & special chars');
});

test('list method works with empty query array', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings' => Http::response([
            'object' => 'list',
            'items' => [],
            'next_page' => null,
            'url' => '/v2/projects/test_project/offerings',
        ], 200),
    ]);

    $response = RevenueCat::offerings()->list();

    expect($response)->toBeInstanceOf(ListPage::class);
    expect(count($response->items()))->toBe(0);
});
