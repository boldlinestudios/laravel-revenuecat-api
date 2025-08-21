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

// Shared test data that mimics RevenueCat API responses
$sampleApp = [
    'object' => 'app',
    'id' => 'app1a2b3c4',
    'name' => 'Test App',
    'created_at' => 1658399423658,
    'type' => 'app_store',
    'project_id' => 'proj1a2b3c4',
    'amazon' => [
        'package_name' => 'com.example.amazonapp',
    ],
    'app_store' => [
        'bundle_id' => 'com.example.iosapp',
    ],
    'mac_app_store' => [
        'bundle_id' => 'com.example.macapp',
    ],
    'play_store' => [
        'package_name' => 'com.example.androidapp',
    ],
    'stripe' => [
        'stripe_account_id' => 'acct_1234567890',
    ],
    'rc_billing' => [
        'stripe_account_id' => 'acct_0987654321',
        'seller_company_name' => 'Example Company',
        'app_name' => 'Example App',
        'seller_company_support_email' => 'support@example.com',
        'support_email' => 'help@example.com',
        'default_currency' => 'USD',
    ],
    'roku' => [
        'roku_channel_id' => '123456',
        'roku_channel_name' => 'Example Channel',
    ],
    'paddle' => [
        'paddle_is_sandbox' => true,
        'paddle_api_key' => 'paddle_api_key_123456789012345678901234567890',
    ],
];

test('list returns response from client', function () use ($sampleApp) {
    $secondApp = array_merge($sampleApp, [
        'id' => 'app2b3c4d5',
        'name' => 'Test App 2',
        'type' => 'play_store',
    ]);

    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps?limit=10' => Http::response([
            'object' => 'list',
            'items' => [$sampleApp, $secondApp],
            'next_page' => '/v2/projects/test_project/apps?starting_after=app2b3c4d5',
            'url' => '/v2/projects/test_project/apps',
        ], 200),
    ]);

    $response = RevenueCatClient::apps()->list(10);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('object'))->toBe('list');
    expect($response->json('items'))->toHaveCount(2);
    expect($response->json('items.0.object'))->toBe('app');
    expect($response->json('items.1.type'))->toBe('play_store');
    expect($response->json('next_page'))->toBe('/v2/projects/test_project/apps?starting_after=app2b3c4d5');
    expect($response->json('url'))->toBe('/v2/projects/test_project/apps');
});

test('create returns response from client', function () use ($sampleApp) {
    $newApp = array_merge($sampleApp, [
        'id' => 'app_new12345',
        'name' => 'My App Store App',
        'created_at' => 1658399423660,
    ]);

    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps' => Http::response($newApp, 201),
    ]);

    $data = [
        'name' => 'My App Store App',
        'type' => 'app_store',
        'app_store' => [
            'bundle_id' => 'com.apple.Pages',
            'shared_secret' => '1234567890abcdef1234567890abcdef',
            'subscription_private_key' => 'private',
            'subscription_key_id' => '6345942CC3',
            'subscription_key_issuer' => '5a049d62-1b9b-453c-b605-1988189d8129',
        ],
    ];
    $response = RevenueCatClient::apps()->create($data);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('app_new12345');
    expect($response->json('object'))->toBe('app');
    expect($response->json('type'))->toBe('app_store');
});

test('get returns response from client with encoded app id', function () use ($sampleApp) {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response($sampleApp, 200),
    ]);

    $appId = 'test-app-id';
    $response = RevenueCatClient::apps()->get($appId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('app1a2b3c4');
    expect($response->json('object'))->toBe('app');
    expect($response->json('type'))->toBe('app_store');
    expect($response->json('project_id'))->toBe('proj1a2b3c4');
    expect($response->json('app_store.bundle_id'))->toBe('com.example.iosapp');
    expect($response->json('rc_billing.default_currency'))->toBe('USD');
});

test('update returns response from client', function () use ($sampleApp) {
    $updatedApp = array_merge($sampleApp, [
        'id' => 'test-app-id',
        'name' => 'Updated App Name',
        'app_store' => [
            'bundle_id' => 'com.example.updatedapp',
        ],
    ]);

    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response($updatedApp, 200),
    ]);

    $appId = 'test-app-id';
    $data = ['name' => 'Updated App Name'];
    $response = RevenueCatClient::apps()->update($appId, $data);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('name'))->toBe('Updated App Name');
    expect($response->json('object'))->toBe('app');
    expect($response->json('app_store.bundle_id'))->toBe('com.example.updatedapp');
});

test('delete returns response from client', function () {
    $deletedApp = [
        'object' => 'app',
        'id' => 'test-app-id',
        'deleted_at' => 1658399423658,
    ];

    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response($deletedApp, 200),
    ]);

    $appId = 'test-app-id';
    $response = RevenueCatClient::apps()->delete($appId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('object'))->toBe('app');
    expect($response->json('id'))->toBe('test-app-id');
    expect($response->json('deleted_at'))->toBe(1658399423658);
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
    $publicKey = [
        'object' => 'public_api_key',
        'id' => 'apikey12345',
        'key' => 'goog_1ab2c3d4',
        'environment' => 'production',
        'app_id' => 'app1a2b3c4',
        'created_at' => 1658399423658,
    ];

    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id/public_api_keys' => Http::response([
            'object' => 'list',
            'items' => [$publicKey],
            'next_page' => '/v2/projects/test_project/apps/test-app-id/public_api_keys?starting_after=apikey12345',
            'url' => '/v2/projects/test_project/apps/test-app-id/public_api_keys',
        ], 200),
    ]);

    $appId = 'test-app-id';
    $response = RevenueCatClient::apps()->listOfPublicKeys($appId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('object'))->toBe('list');
    expect($response->json('items'))->toHaveCount(1);
    expect($response->json('items.0.object'))->toBe('public_api_key');
    expect($response->json('items.0.key'))->toBe('goog_1ab2c3d4');
    expect($response->json('items.0.environment'))->toBe('production');
    expect($response->json('items.0.app_id'))->toBe('app1a2b3c4');
    expect($response->json('next_page'))->toBe('/v2/projects/test_project/apps/test-app-id/public_api_keys?starting_after=apikey12345');
    expect($response->json('url'))->toBe('/v2/projects/test_project/apps/test-app-id/public_api_keys');
});

test('get method properly encodes special characters in app id', function () use ($sampleApp) {
    $specialApp = array_merge($sampleApp, [
        'id' => 'test app with spaces & special chars',
        'name' => 'Special App',
    ]);

    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test%20app%20with%20spaces%20%26%20special%20chars' => Http::response($specialApp, 200),
    ]);

    $appId = 'test app with spaces & special chars';
    $response = RevenueCatClient::apps()->get($appId);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('id'))->toBe('test app with spaces & special chars');
    expect($response->json('object'))->toBe('app');
    expect($response->json('type'))->toBe('app_store');
});

test('list method works with empty query array', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps' => Http::response([
            'object' => 'list',
            'items' => [],
            'next_page' => null,
            'url' => '/v2/projects/test_project/apps',
        ], 200),
    ]);

    $response = RevenueCatClient::apps()->list();

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('object'))->toBe('list');
    expect($response->json('items'))->toBe([]);
    expect($response->json('url'))->toBe('/v2/projects/test_project/apps');
});
