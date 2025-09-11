<?php

namespace BoldLineStudios\RevenueCatApi\Endpoints\Concerns;

use BoldLineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

trait Retrievable
{
    abstract protected function client(): RevenueCatClient;

    abstract protected function basePath(): string;

    /**
     * Get a resource by id (raw Response).
     */
    public function getRaw(string $id): Response
    {
        $id = rawurlencode($id);

        return $this->client()->get($this->basePath()."/{$id}");
    }
}
