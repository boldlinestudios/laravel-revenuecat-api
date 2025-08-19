<?php

use BoldlineStudios\RevenueCatApi\Facades\RevenueCatClient;
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

test('list returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings?limit=10' => Http::response([
            'offerings' => [
                ['id' => 'offering1', 'identifier' => 'default'],
                ['id' => 'offering2', 'identifier' => 'premium'],
            ],
        ], 200),
    ]);

    $query = ['limit' => 10];
    $response = RevenueCatClient::offerings()->list($query);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('offerings'))->toHaveCount(2);
});

test('create returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings' => Http::response([
            'id' => 'new_offering_id',
            'identifier' => 'new_premium',
            'created_at' => '2024-01-01T00:00:00Z',
        ], 201),
    ]);

    $data = ['identifier' => 'new_premium', 'description' => 'Premium offering'];
    $response = RevenueCatClient::offerings()->create($data);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('new_offering_id');
});

test('get returns response from client with encoded offering id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings/test-offering-id' => Http::response([
            'id' => 'test-offering-id',
            'identifier' => 'premium',
            'description' => 'Premium offering',
        ], 200),
    ]);

    $offeringId = 'test-offering-id';
    $response = RevenueCatClient::offerings()->get($offeringId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('test-offering-id');
});

test('update returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings/test-offering-id' => Http::response([
            'id' => 'test-offering-id',
            'identifier' => 'premium_plus',
            'description' => 'Updated premium offering',
        ], 200),
    ]);

    $offeringId = 'test-offering-id';
    $data = ['description' => 'Updated premium offering'];
    $response = RevenueCatClient::offerings()->update($offeringId, $data);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('description'))->toBe('Updated premium offering');
});

test('delete returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings/test-offering-id' => Http::response([], 204),
    ]);

    $offeringId = 'test-offering-id';
    $response = RevenueCatClient::offerings()->delete($offeringId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->status())->toBe(204);
});

test('get method properly encodes special characters in offering id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings/test%20offering%20with%20spaces%20%26%20special%20chars' => Http::response([
            'id' => 'test offering with spaces & special chars',
            'identifier' => 'special_offering',
        ], 200),
    ]);

    $offeringId = 'test offering with spaces & special chars';
    $response = RevenueCatClient::offerings()->get($offeringId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('test offering with spaces & special chars');
});

test('list method works with empty query array', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/offerings' => Http::response([
            'offerings' => [],
        ], 200),
    ]);

    $response = RevenueCatClient::offerings()->list();

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('offerings'))->toBe([]);
});
