<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints\Concerns;

use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

trait Creatable
{
    abstract protected function client(): RevenueCatClient;

    abstract protected function basePath(): string;

    /**
     * Create a resource.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Response
    {
        return $this->client()->post($this->basePath(), $data);
    }
}
