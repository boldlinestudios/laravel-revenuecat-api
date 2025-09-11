<?php

use BoldLineStudios\RevenueCatApi\Data\App\StoreKitConfigData;
use BoldLineStudios\RevenueCatApi\Data\AppData;
use BoldLineStudios\RevenueCatApi\Data\ListPage;
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

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

test('list returns ListPage of AppData', function () use ($sampleApp) {
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

    $list = RevenueCat::apps()->all(10);

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
});

test('create returns AppData DTO', function () use ($sampleApp) {
    $newApp = array_merge($sampleApp, [
        'object' => 'app',
        'id' => 'app_new12345',
        'name' => 'My App Store App',
        'created_at' => 1658399423660,
        'type' => 'app_store',
        'project_id' => 'proj1a2b3c4',
        'app_store' => [
            'bundle_id' => 'com.apple.Pages',
        ],
    ]);

    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps' => Http::response($newApp, 201),
    ]);

    $storeConfig = [
        'app_store' => [
            'bundle_id' => 'com.apple.Pages',
            'shared_secret' => '1234567890abcdef1234567890abcdef',
            'subscription_private_key' => 'private',
            'subscription_key_id' => '6345942CC3',
            'subscription_key_issuer' => '5a049d62-1b9b-453c-b605-1988189d8129',
        ],
    ];
    $app = RevenueCat::apps()->create('My App Store App', 'app_store', $storeConfig);

    expect($app)->toBeInstanceOf(AppData::class);
    expect($app->getId())->toBe('app_new12345');
    expect($app->getType())->toBe('app_store');
    expect($app->getProjectId())->toBe('proj1a2b3c4');
    expect(($app->getAppStore() ?? [])['bundle_id'] ?? null)->toBe('com.apple.Pages');

});

test('get returns AppData with encoded app id', function () use ($sampleApp) {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response($sampleApp, 200),
    ]);

    $appId = 'test-app-id';
    $app = RevenueCat::apps()->get($appId);

    expect($app)->toBeInstanceOf(AppData::class);
    expect($app->getId())->toBe('app1a2b3c4');
    expect($app->getType())->toBe('app_store');
    expect($app->getProjectId())->toBe('proj1a2b3c4');
    expect(($app->getAppStore() ?? [])['bundle_id'] ?? null)->toBe('com.example.iosapp');
    expect(($app->getRcBilling() ?? [])['default_currency'] ?? null)->toBe('USD');
});

test('update returns AppData DTO', function () use ($sampleApp) {
    $updatedApp = array_merge($sampleApp, [
        'object' => 'app',
        'id' => 'test-app-id',
        'name' => 'Updated App Name',
        'type' => 'app_store',
        'project_id' => 'proj1a2b3c4',
        'app_store' => [
            'bundle_id' => 'com.example.updatedapp',
        ],
    ]);

    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response($updatedApp, 200),
    ]);

    $appId = 'test-app-id';
    $data = ['name' => 'Updated App Name'];
    $app = RevenueCat::apps()->update($appId, 'Updated App Name', $data);

    expect($app)->toBeInstanceOf(AppData::class);
    expect($app->getName())->toBe('Updated App Name');
    expect(($app->getAppStore() ?? [])['bundle_id'] ?? null)->toBe('com.example.updatedapp');
    expect($app->getType())->toBe('app_store');
    expect($app->getProjectId())->toBe('proj1a2b3c4');
});

test('delete returns true when deletion succeeds', function () {
    $deletedApp = [
        'object' => 'app',
        'id' => 'test-app-id',
        'deleted_at' => 1658399423658,
    ];

    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response($deletedApp, 200),
    ]);

    $appId = 'test-app-id';
    $deleted = RevenueCat::apps()->delete($appId);

    expect($deleted)->toBeTrue();
});

test('getStoreKitConfig returns StoreKitConfigData for Apple apps', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response([
            'object' => 'app',
            'id' => 'test-app-id',
            'type' => 'app_store', // Apple app
        ], 200),
        'https://api.example.com/v2/projects/test_project/apps/test-app-id/store_kit_config' => Http::response([
            'object' => 'store_kit_config_file',
            'contents' => [
                'shared_secret' => 'test_secret',
                'bundle_id' => 'com.example.app',
            ],
        ], 200),
    ]);

    $appId = 'test-app-id';
    $config = RevenueCat::apps()->getStoreKitConfig($appId);

    expect($config)->toBeInstanceOf(StoreKitConfigData::class);
    expect($config->getResourceType())->toBe('store_kit_config_file');
    expect($config->getContents())->toBeArray();
    expect($config->getContents()['shared_secret'])->toBe('test_secret');
});

test('getStoreKitConfig throws exception for non-Apple apps', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response([
            'object' => 'app',
            'id' => 'test-app-id',
            'type' => 'play_store', // Android app
        ], 200),
    ]);

    $appId = 'test-app-id';

    expect(fn () => RevenueCat::apps()->getStoreKitConfig($appId))
        ->toThrow(\InvalidArgumentException::class, 'StoreKit config is only available for Apple apps');
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
    $publicKeys = RevenueCat::apps()->listOfPublicKeys($appId);

    expect($publicKeys)->toBeInstanceOf(ListPage::class);
    expect($publicKeys->items()[0]->getResourceType())->toBe('public_api_key');
    expect($publicKeys->items()[0]->getKey())->toBe('goog_1ab2c3d4');
    expect($publicKeys->items()[0]->getEnvironment())->toBe('production');
    expect($publicKeys->items()[0]->getAppId())->toBe('app1a2b3c4');
    expect($publicKeys->nextCursor())->toBe('apikey12345');
    expect($publicKeys->url())->toBe('/v2/projects/test_project/apps/test-app-id/public_api_keys');
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
    $app = RevenueCat::apps()->get($appId);

    expect($app)->toBeInstanceOf(AppData::class);
    expect($app->getId())->toBe('test app with spaces & special chars');
    expect($app->getType())->toBe('app_store');
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

    $list = RevenueCat::apps()->all();

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(0);
});
