<?php

use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
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
        'https://api.example.com/v2/projects?limit=10' => Http::response([
            'projects' => [
                ['id' => 'project1', 'name' => 'Test Project 1'],
                ['id' => 'project2', 'name' => 'Test Project 2'],
            ],
        ], 200),
    ]);

    $response = RevenueCat::projects()->list(10);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('projects'))->toHaveCount(2);
});

test('list method works with empty query array', function () {
    Http::fake([
        'https://api.example.com/v2/projects' => Http::response([
            'projects' => [],
        ], 200),
    ]);

    $response = RevenueCat::projects()->list();

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('projects'))->toBe([]);
});

test('list method works with no parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects' => Http::response([
            'projects' => [
                ['id' => 'project1', 'name' => 'Test Project 1'],
            ],
        ], 200),
    ]);

    $response = RevenueCat::projects()->list();

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('projects'))->toHaveCount(1);
});

test('list method works with no parameters using class method', function () {
    Http::fake([
        'https://api.example.com/v2/projects' => Http::response([
            'projects' => [
                ['id' => 'project1', 'name' => 'Test Project 1'],
            ],
        ], 200),
    ]);

    $client = app(\BoldlineStudios\RevenueCatApi\Http\RevenueCatClient::class);

    $response = $client->projects()->list();

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->successful())->toBeTrue();
    expect($response->json('projects'))->toHaveCount(1);
});
