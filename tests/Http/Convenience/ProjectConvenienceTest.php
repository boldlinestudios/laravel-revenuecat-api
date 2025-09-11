<?php

use BoldLineStudios\RevenueCatApi\Data\ListPage;
use BoldLineStudios\RevenueCatApi\Data\ProjectData;
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

test('listProjects calls projects()->all() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects?limit=10' => Http::response([
            'object' => 'list',
            'items' => [
                ['object' => 'project', 'id' => 'proj1', 'name' => 'Project 1', 'created_at' => 1658399423658],
                ['object' => 'project', 'id' => 'proj2', 'name' => 'Project 2', 'created_at' => 1658399423658],
            ],
            'url' => '/v2/projects',
        ], 200),
    ]);

    $list = RevenueCat::listProjects(10);

    expect($list)->toBeInstanceOf(ListPage::class);
    expect($list->items())->toHaveCount(2);
    expect($list->items()[0])->toBeInstanceOf(ProjectData::class);
    expect($list->items()[0]->getId())->toBe('proj1');
    expect($list->items()[0]->getName())->toBe('Project 1');
    expect($list->items()[0]->getCreatedAtMs())->toBe(1658399423658);
    expect($list->items()[1])->toBeInstanceOf(ProjectData::class);
    expect($list->items()[1]->getId())->toBe('proj2');
    expect($list->items()[1]->getName())->toBe('Project 2');
    expect($list->items()[1]->getCreatedAtMs())->toBe(1658399423658);
});
