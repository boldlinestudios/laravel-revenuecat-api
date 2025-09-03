<?php

use BoldlineStudios\RevenueCatApi\Data\App\PublicApiKeyData;
use BoldlineStudios\RevenueCatApi\Data\AppData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

test('getApp calls apps()->get() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response([
            'object' => 'app',
            'id' => 'test-app-id',
            'name' => 'Test App',
        ], 200),
    ]);

    $response = RevenueCat::getApp('test-app-id');

    expect($response)->toBeInstanceOf(AppData::class);
    expect($response->getId())->toBe('test-app-id');
});

test('listApps calls apps()->all() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps?limit=10' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'app', 'id' => 'app1', 'name' => 'App 1'],
                ['object' => 'app', 'id' => 'app2', 'name' => 'App 2'],
            ],
        ], 200),
    ]);

    $apps = RevenueCat::listApps(10);

    expect($apps)->toBeInstanceOf(ListPage::class);
    expect($apps->items())->toHaveCount(2);
});

test('createApp calls apps()->create() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps' => Http::response([
            'object' => 'app',
            'id' => 'new-app-id',
            'name' => 'New App',
            'type' => 'app_store',
            'project_id' => 'proj1a2b3c4',
            'app_store' => [
                'bundle_id' => 'com.example.app',
            ],
            'created_at' => 1658399423660,
        ], 201),
    ]);

    $storeConfig = ['app_store' => ['bundle_id' => 'com.example.app']];
    $app = RevenueCat::createApp('New App', 'app_store', $storeConfig);

    expect($app)->toBeInstanceOf(AppData::class);
    expect($app->getId())->toBe('new-app-id');
    expect($app->getType())->toBe('app_store');
    expect($app->getProjectId())->toBe('proj1a2b3c4');
    expect(($app->getAppStore() ?? [])['bundle_id'] ?? null)->toBe('com.example.app');
    expect($app->getCreatedAtMs())->toBe(1658399423660);
});

test('updateApp calls apps()->update() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response([
            'object' => 'app',
            'id' => 'test-app-id',
            'name' => 'Updated App',
            'play_store' => [
                'package_name' => 'com.example.app',
            ],
            'type' => 'play_store',
            'project_id' => 'proj1a2b3c4',
            'created_at' => 1658399423660,
        ], 200),
    ]);

    $storeConfig = ['play_store' => ['package_name' => 'com.example.app']];
    $app = RevenueCat::updateApp('test-app-id', 'Updated App', $storeConfig);

    expect($app)->toBeInstanceOf(AppData::class);
    expect($app->getName())->toBe('Updated App');
    expect($app->getType())->toBe('play_store');
    expect($app->getProjectId())->toBe('proj1a2b3c4');
    expect(($app->getPlayStore() ?? [])['package_name'] ?? null)->toBe('com.example.app');
    expect($app->getCreatedAtMs())->toBe(1658399423660);
});

test('deleteApp calls apps()->delete() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id' => Http::response([
            'object' => 'app',
            'id' => 'test-app-id',
            'deleted_at' => 1658399423658,
        ], 200),
    ]);

    $deleted = RevenueCat::deleteApp('test-app-id');

    expect($deleted)->toBeTrue();
});

test('getAppStoreKitConfig calls apps()->storeKitConfig() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id/store_kit_config' => Http::response([
            'config' => 'store_kit_configuration_data',
        ], 200),
    ]);

    $response = RevenueCat::getAppStoreKitConfig('test-app-id');

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('config'))->toBe('store_kit_configuration_data');
});

test('listAppPublicKeys calls apps()->listOfPublicKeys() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/apps/test-app-id/public_api_keys' => Http::response([
            'object' => 'list',
            'items' => [
                [
                    'id' => 'key1',
                    'object' => 'public_api_key',
                    'key' => 'pk_test_123',
                    'environment' => 'production',
                    'app_id' => 'app1a2b3c4',
                    'created_at' => 1658399423658,
                ],
            ],
        ], 200),
    ]);

    $publicKeys = RevenueCat::listAppPublicKeys('test-app-id');

    expect($publicKeys)->toBeInstanceOf(ListPage::class);
    expect($publicKeys->items())->toHaveCount(1);
    expect($publicKeys->items()[0])->toBeInstanceOf(PublicApiKeyData::class);
});
