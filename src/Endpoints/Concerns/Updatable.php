<?php

namespace BoldLineStudios\RevenueCatApi\Endpoints\Concerns;

use BoldLineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

trait Updatable
{
    abstract protected function client(): RevenueCatClient;

    abstract protected function basePath(): string;

    /**
     * Update a resource by id (raw Response).
     *
     * @param  array<string, mixed>  $data
     */
    public function updateRaw(string $id, array $data): Response
    {
        $id = rawurlencode($id);

        return $this->client()->post($this->basePath()."/{$id}", $data);
    }
}
