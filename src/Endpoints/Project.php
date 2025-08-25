<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\ProjectData;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;

class Project
{
    use Listable;

    public function __construct(private RevenueCatClient $client) {}

    protected function client(): RevenueCatClient
    {
        return $this->client;
    }

    protected function basePath(): string
    {
        return '/projects';
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<ProjectData>
     */
    public function list(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->listAsDto(ProjectData::class, $limit, $startingAfter, $extra, 'projects', 'next_page', 'url');
    }
}
