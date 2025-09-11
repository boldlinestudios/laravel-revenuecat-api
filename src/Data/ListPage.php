<?php

namespace BoldLineStudios\RevenueCatApi\Data;

use Illuminate\Http\Client\Response;

/**
 * @template T of object
 */
class ListPage
{
    /**
     * @param  array<int, T>  $items
     */
    public function __construct(
        private array $items,
        private ?string $nextCursor,
        private string $url,
        private Response $rawResponse,
    ) {}

    /**
     * @return array<int, T>
     */
    public function items(): array
    {
        return $this->items;
    }

    public function nextCursor(): ?string
    {
        return $this->nextCursor;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function raw(): Response
    {
        return $this->rawResponse;
    }
}
