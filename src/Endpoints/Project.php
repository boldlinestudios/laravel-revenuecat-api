<?php

namespace BoldLineStudios\RevenueCatApi\Endpoints;

use BoldLineStudios\RevenueCatApi\Data\ListPage;
use BoldLineStudios\RevenueCatApi\Data\ProjectData;
use BoldLineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldLineStudios\RevenueCatApi\Http\RevenueCatClient;

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
    public function all(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        /** @var ListPage<ProjectData> */
        return $this->listAsDto(ProjectData::class, $limit, $startingAfter, $extra);
    }
}
