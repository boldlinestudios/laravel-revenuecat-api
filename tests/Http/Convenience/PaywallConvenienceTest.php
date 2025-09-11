<?php

use BoldLineStudios\RevenueCatApi\Data\PaywallData;
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

test('createPaywall calls paywalls()->create() with correct parameters', function () {
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

    $paywall = RevenueCat::createPaywall('ofrng123456789a');

    expect($paywall)->toBeInstanceOf(PaywallData::class);
    expect($paywall->getId())->toBe('pw123456789abcdef');
    expect($paywall->getName())->toBe('My Awesome Paywall');
    expect($paywall->getOfferingId())->toBe('ofrng123456789a');
    expect($paywall->getCreatedAtMs())->toBe(1658399423658);
    expect($paywall->getPublishedAtMs())->toBe(1658399423958);
});
