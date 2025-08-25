<?php

use BoldlineStudios\RevenueCatApi\Data\ListPage;
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

test('list returns ListPage of ProjectData', function () {
    Http::fake([
        'https://api.example.com/v2/projects?limit=10' => Http::response([
            'object' => 'list',
            'projects' => [
                ['id' => 'project1', 'name' => 'Test Project 1'],
                ['id' => 'project2', 'name' => 'Test Project 2'],
            ],
            'next_page' => null,
            'url' => '/v2/projects',
        ], 200),
    ]);

    $list = RevenueCat::projects()->list(10);

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
});

test('list method works with empty query array', function () {
    Http::fake([
        'https://api.example.com/v2/projects' => Http::response([
            'object' => 'list',
            'projects' => [],
            'next_page' => null,
            'url' => '/v2/projects',
        ], 200),
    ]);

    $list = RevenueCat::projects()->list();

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(0);
});

test('list method works with no parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects' => Http::response([
            'object' => 'list',
            'projects' => [
                ['id' => 'project1', 'name' => 'Test Project 1'],
            ],
            'next_page' => null,
            'url' => '/v2/projects',
        ], 200),
    ]);

    $list = RevenueCat::projects()->list();

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(1);
});

test('list method works with no parameters using class method', function () {
    Http::fake([
        'https://api.example.com/v2/projects' => Http::response([
            'object' => 'list',
            'projects' => [
                ['id' => 'project1', 'name' => 'Test Project 1'],
            ],
            'next_page' => null,
            'url' => '/v2/projects',
        ], 200),
    ]);

    $client = app(\BoldlineStudios\RevenueCatApi\Http\RevenueCatClient::class);

    $list = $client->projects()->list();

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(1);
});
