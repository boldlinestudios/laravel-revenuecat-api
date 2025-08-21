<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints\Concerns;

use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

trait Retrievable
{
    abstract protected function client(): RevenueCatClient;

    abstract protected function basePath(): string;

    /**
     * Get a resource by id.
     */
    public function get(string $id): Response
    {
        $id = rawurlencode($id);

        return $this->client()->get($this->basePath()."/{$id}");
    }
}
