<?php

use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\OfferingData;
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

test('getOffering calls offerings()->get() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings/test-offering-id' => Http::response([
            'object' => 'offering',
            'id' => 'test-offering-id',
            'lookup_key' => 'basic',
            'display_name' => 'Basic Plan',
        ], 200),
    ]);

    $response = RevenueCat::getOffering('test-offering-id');

    expect($response)->toBeInstanceOf(OfferingData::class);
    expect($response->getId())->toBe('test-offering-id');
});

test('listOfferings calls offerings()->list() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings?limit=10' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'offering', 'id' => 'off1', 'lookup_key' => 'basic', 'display_name' => 'Basic Plan'],
                ['object' => 'offering', 'id' => 'off2', 'lookup_key' => 'pro', 'display_name' => 'Pro Plan'],
            ],
        ], 200),
    ]);

    $response = RevenueCat::listOfferings(10);

    expect($response)->toBeInstanceOf(ListPage::class);
    expect(count($response->items()))->toBe(2);
});

test('createOffering calls offerings()->create() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings' => Http::response([
            'object' => 'offering',
            'id' => 'new-offering-id',
            'lookup_key' => 'basic',
            'display_name' => 'New Plan',
            'metadata' => ['color' => 'blue', 'call_to_action' => 'Get it now'],
        ], 201),
    ]);

    $response = RevenueCat::createOffering(
        'basic',
        'New Plan',
        ['color' => 'blue', 'call_to_action' => 'Get it now']);

    expect($response)->toBeInstanceOf(OfferingData::class);
    expect($response->getId())->toBe('new-offering-id');
    expect($response->getLookupKey())->toBe('basic');
    expect($response->getDisplayName())->toBe('New Plan');
    expect($response->getMetadata())->toBe(['color' => 'blue', 'call_to_action' => 'Get it now']);
});

test('updateOffering calls offerings()->update() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings/test-offering-id' => Http::response([
            'object' => 'offering',
            'id' => 'test-offering-id',
            'lookup_key' => 'basic',
            'display_name' => 'Updated Plan',
            'is_current' => true,
            'metadata' => ['color' => 'red'],
        ], 200),
    ]);

    $offering = RevenueCat::updateOffering('test-offering-id', 'Updated Plan', true, ['color' => 'red']);

    expect($offering)->toBeInstanceOf(OfferingData::class);
    expect($offering->getDisplayName())->toBe('Updated Plan');
    expect($offering->isCurrent())->toBeTrue();
    expect($offering->getMetadata())->toBe(['color' => 'red']);
});

test('deleteOffering calls offerings()->delete() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings/test-offering-id' => Http::response([
            'object' => 'offering',
            'id' => 'test-offering-id',
            'deleted_at' => 1658399423658,
        ], 200),
    ]);

    $deleted = RevenueCat::deleteOffering('test-offering-id');

    expect($deleted)->toBeTrue();
});
