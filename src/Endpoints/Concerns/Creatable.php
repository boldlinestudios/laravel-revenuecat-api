<?php

namespace BoldLineStudios\RevenueCatApi\Endpoints\Concerns;

use BoldLineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

trait Creatable
{
    abstract protected function client(): RevenueCatClient;

    abstract protected function basePath(): string;

    /**
     * Create a resource (raw Response).
     *
     * @param  array<string, mixed>  $data
     */
    public function createRaw(array $data): Response
    {
        return $this->client()->post($this->basePath(), $data);
    }
}
