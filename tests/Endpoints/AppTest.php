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
        'https://api.example.com/v2/projects/test_project/apps?limit=10' => Http::response([
            'apps' => [
                ['id' => 'app1', 'name' => 'Test App 1'],
                ['id' => 'app2', 'name' => 'Test App 2'],
            ],
        ], 200),
    ]);

    $query = ['limit' => 10];
    $response = RevenueCatClient::apps()->list($query);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('apps'))->toHaveCount(2);
});

test('create returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps' => Http::response([
            'id' => 'new_app_id',
            'name' => 'Test App',
            'type' => 'ios',
        ], 201),
    ]);

    $data = ['name' => 'Test App', 'type' => 'ios'];
    $response = RevenueCatClient::apps()->create($data);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('new_app_id');
});

test('get returns response from client with encoded app id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response([
            'id' => 'test-app-id',
            'name' => 'Test App',
            'type' => 'ios',
        ], 200),
    ]);

    $appId = 'test-app-id';
    $response = RevenueCatClient::apps()->get($appId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('test-app-id');
});

test('update returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response([
            'id' => 'test-app-id',
            'name' => 'Updated App Name',
            'type' => 'ios',
        ], 200),
    ]);

    $appId = 'test-app-id';
    $data = ['name' => 'Updated App Name'];
    $response = RevenueCatClient::apps()->update($appId, $data);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('name'))->toBe('Updated App Name');
});

test('delete returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response([], 204),
    ]);

    $appId = 'test-app-id';
    $response = RevenueCatClient::apps()->delete($appId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->status())->toBe(204);
});

test('storeKitConfig returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id/store_kit_config' => Http::response([
            'config' => 'store_kit_configuration_data',
        ], 200),
    ]);

    $appId = 'test-app-id';
    $response = RevenueCatClient::apps()->storeKitConfig($appId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('config'))->toBe('store_kit_configuration_data');
});

test('listOfPublicKeys returns response from client', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id/public_api_keys' => Http::response([
            'public_keys' => [
                ['key' => 'pk_test_123', 'type' => 'test'],
                ['key' => 'pk_live_456', 'type' => 'live'],
            ],
        ], 200),
    ]);

    $appId = 'test-app-id';
    $response = RevenueCatClient::apps()->listOfPublicKeys($appId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('public_keys'))->toHaveCount(2);
});

test('get method properly encodes special characters in app id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test%20app%20with%20spaces%20%26%20special%20chars' => Http::response([
            'id' => 'test app with spaces & special chars',
            'name' => 'Special App',
        ], 200),
    ]);

    $appId = 'test app with spaces & special chars';
    $response = RevenueCatClient::apps()->get($appId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('test app with spaces & special chars');
});

test('list method works with empty query array', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps' => Http::response([
            'apps' => [],
        ], 200),
    ]);

    $response = RevenueCatClient::apps()->list();

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('apps'))->toBe([]);
});
