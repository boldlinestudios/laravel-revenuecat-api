<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints\Concerns;

use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

trait Listable
{
    abstract protected function client(): RevenueCatClient;

    abstract protected function basePath(): string;

    /**
     * List resources (raw Response).
     *
     * @param  int  $limit  Number of items to return (default 20)
     * @param  string|null  $startingAfter  Cursor id to continue after
     * @param  array<string, mixed>  $extra  Additional query parameters to merge
     */
    public function allRaw(int $limit = 20, ?string $startingAfter = null, array $extra = []): Response
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

    /**
     * @param  class-string  $dtoClass  Must have static fromArray(array): object
     * @param  array<string,mixed>  $extra
     * @return ListPage<object>
     */
    protected function listAsDto(
        string $dtoClass,
        int $limit = 20,
        ?string $startingAfter = null,
        array $extra = [],
        string $itemsKey = 'items',
        string $nextPageKey = 'next_page',
        string $urlKey = 'url'
    ): ListPage {
        $response = $this->allRaw($limit, $startingAfter, $extra);
        $payload = $response->json();
        $payload = is_array($payload) ? $payload : [];

        $items = $payload[$itemsKey] ?? [];
        $items = is_array($items) ? $items : [];

        /** @var array<int, object> $dtos */
        $dtos = [];
        foreach ($items as $item) {
            if (is_array($item)) {
                // @phpstan-ignore-next-line - static generic constructor
                $dtos[] = $dtoClass::fromArray($item);
            }
        }

        $next = isset($payload[$nextPageKey]) && is_string($payload[$nextPageKey]) ? $payload[$nextPageKey] : null;
        $url = isset($payload[$urlKey]) && is_string($payload[$urlKey]) ? $payload[$urlKey] : $this->basePath();

        return new ListPage($dtos, $next, $url, $response);
    }

    /**
     * @param  class-string  $dtoClass
     * @return ListPage<object>
     */
    protected function listFromResponse(
        Response $response,
        string $dtoClass,
        string $itemsKey = 'items',
        string $nextPageKey = 'next_page',
        string $urlKey = 'url'
    ): ListPage {
        $payload = $response->json();
        $payload = is_array($payload) ? $payload : [];

        $items = $payload[$itemsKey] ?? [];
        $items = is_array($items) ? $items : [];

        /** @var array<int, object> $dtos */
        $dtos = [];
        foreach ($items as $item) {
            if (is_array($item)) {
                // @phpstan-ignore-next-line - static generic constructor
                $dtos[] = $dtoClass::fromArray($item);
            }
        }

        $next = isset($payload[$nextPageKey]) && is_string($payload[$nextPageKey]) ? $payload[$nextPageKey] : null;
        $url = isset($payload[$urlKey]) && is_string($payload[$urlKey]) ? $payload[$urlKey] : $this->basePath();

        return new ListPage($dtos, $next, $url, $response);
    }

    /**
     * Build the standard list query parameters used by list endpoints.
     *
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    protected function buildListQuery(int $limit = 20, ?string $startingAfter = null, array $extra = []): array
    {
        $query = [];

        if ($limit !== 20) {
            $query['limit'] = $limit;
        }

        if ($startingAfter !== null && $startingAfter !== '') {
            $query['starting_after'] = $startingAfter;
        }

        if ($extra !== []) {
            $query = array_merge($query, $extra);
        }

        return $query;
    }

    /**
     * Execute a list request for a custom path (not basePath()).
     *
     * @param  array<string, mixed>  $extra
     */
    protected function allRawForPath(string $path, int $limit = 20, ?string $startingAfter = null, array $extra = []): Response
    {
        return $this->client()->get($path, $this->buildListQuery($limit, $startingAfter, $extra));
    }

    /**
     * Return a ListPage for a custom path (not basePath()).
     *
     * @param  class-string  $dtoClass
     * @param  array<string, mixed>  $extra
     * @return ListPage<object>
     */
    protected function listPageForPath(
        string $path,
        string $dtoClass,
        int $limit = 20,
        ?string $startingAfter = null,
        array $extra = [],
        string $itemsKey = 'items',
        string $nextPageKey = 'next_page',
        string $urlKey = 'url'
    ): ListPage {
        $response = $this->allRawForPath($path, $limit, $startingAfter, $extra);

        return $this->listFromResponse($response, $dtoClass, $itemsKey, $nextPageKey, $urlKey);
    }
}
