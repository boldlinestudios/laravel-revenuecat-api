<?php

use BoldlineStudios\RevenueCatApi\Data\PaywallData;
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

test('create returns PaywallData DTO', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/paywalls' => Http::response([
            'object' => 'paywall',
            'id' => 'pw123456789abcdef',
            'name' => 'My Awesome Paywall',
            'offering_id' => 'ofrng123456789a',
            'created_at' => 1658399423658,
            'published_at' => 1658399423958,
        ], 201),
    ]);

    $paywall = RevenueCat::paywalls()->create('ofrng123456789a');

    expect($paywall)->toBeInstanceOf(PaywallData::class);
    expect($paywall->getId())->toBe('pw123456789abcdef');
    expect($paywall->getName())->toBe('My Awesome Paywall');
    expect($paywall->getOfferingId())->toBe('ofrng123456789a');
    expect($paywall->getCreatedAtMs())->toBe(1658399423658);
    expect($paywall->getPublishedAtMs())->toBe(1658399423958);
});

test('create returns PaywallData with null name and published_at', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/paywalls' => Http::response([
            'object' => 'paywall',
            'id' => 'pw987654321fedcba',
            'offering_id' => 'ofrng987654321b',
            'created_at' => 1658399423658,
        ], 201),
    ]);

    $paywall = RevenueCat::paywalls()->create('ofrng987654321b');

    expect($paywall)->toBeInstanceOf(PaywallData::class);
    expect($paywall->getId())->toBe('pw987654321fedcba');
    expect($paywall->getName())->toBeNull();
    expect($paywall->getOfferingId())->toBe('ofrng987654321b');
    expect($paywall->getCreatedAtMs())->toBe(1658399423658);
    expect($paywall->getPublishedAtMs())->toBeNull();
});
