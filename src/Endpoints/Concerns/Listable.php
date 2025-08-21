<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints\Concerns;

use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

trait Listable
{
    abstract protected function client(): RevenueCatClient;

    abstract protected function basePath(): string;

    /**
     * List resources.
     *
     * @param  int  $limit  Number of items to return (default 20)
     * @param  string|null  $startingAfter  Cursor id to continue after
     * @param  array<string, mixed>  $extra  Additional query parameters to merge
     */
    public function list(int $limit = 20, ?string $startingAfter = null, array $extra = []): Response
    {
        $query = [];

        // Only include limit if it differs from the documented default (20)
        if ($limit !== 20) {
            $query['limit'] = $limit;
        }

        if ($startingAfter !== null && $startingAfter !== '') {
            $query['starting_after'] = $startingAfter;
        }

        if ($extra !== []) {
            $query = array_merge($query, $extra);
        }

        return $this->client()->get($this->basePath(), $query);
    }
}
