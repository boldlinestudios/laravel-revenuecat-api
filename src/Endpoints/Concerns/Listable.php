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
    public function listRaw(int $limit = 20, ?string $startingAfter = null, array $extra = []): Response
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
     * @template T of object
     *
     * @param  class-string<T>  $dtoClass  Must have static fromArray(array): T
     * @param  array<string,mixed>  $extra
     * @return ListPage<T>
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
        $response = $this->listRaw($limit, $startingAfter, $extra);
        $payload = $response->json();
        $payload = is_array($payload) ? $payload : [];

        $items = $payload[$itemsKey] ?? [];
        $items = is_array($items) ? $items : [];

        $dtos = [];
        foreach ($items as $item) {
            if (is_array($item)) {
                /** @var T $dto */
                // @phpstan-ignore-next-line
                $dto = $dtoClass::fromArray($item);
                $dtos[] = $dto;
            }
        }

        $next = isset($payload[$nextPageKey]) && is_string($payload[$nextPageKey]) ? $payload[$nextPageKey] : null;
        $url = isset($payload[$urlKey]) && is_string($payload[$urlKey]) ? $payload[$urlKey] : $this->basePath();

        return new ListPage($dtos, $next, $url, $response);
    }
}
