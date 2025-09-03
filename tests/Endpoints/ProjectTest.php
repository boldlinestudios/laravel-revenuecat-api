<?php

use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

test('list returns ListPage of ProjectData', function () {
    Http::fake([
        'https://api.example.com/v2/projects?limit=10' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'project', 'id' => 'project1', 'name' => 'Test Project 1', 'created_at' => 1658399423658],
                ['object' => 'project', 'id' => 'project2', 'name' => 'Test Project 2', 'created_at' => 1658399423658],
            ],
            'next_page' => null,
            'url' => '/v2/projects',
        ], 200),
    ]);

    $list = RevenueCat::projects()->all(10);

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(2);
});

test('list method works with empty query array', function () {
    Http::fake([
        'https://api.example.com/v2/projects' => Http::response([
            'object' => 'list',
            'items' => [],
            'next_page' => null,
            'url' => '/v2/projects',
        ], 200),
    ]);

    $list = RevenueCat::projects()->all();

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(0);
});

test('list method works with no parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'project', 'id' => 'project1', 'name' => 'Test Project 1', 'created_at' => 1658399423658],
            ],
            'next_page' => null,
            'url' => '/v2/projects',
        ], 200),
    ]);

    $list = RevenueCat::projects()->all();

    expect($list)->toBeInstanceOf(ListPage::class);
    expect($list->items())->toHaveCount(1);
});

test('list method works with no parameters using class method', function () {
    Http::fake([
        'https://api.example.com/v2/projects' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'project', 'id' => 'project1', 'name' => 'Test Project 1', 'created_at' => 1658399423658],
            ],
            'next_page' => null,
            'url' => '/v2/projects',
        ], 200),
    ]);

    $client = app(\BoldlineStudios\RevenueCatApi\Http\RevenueCatClient::class);

    $list = $client->projects()->all();

    expect($list)->toBeInstanceOf(ListPage::class);
    expect(count($list->items()))->toBe(1);
});
