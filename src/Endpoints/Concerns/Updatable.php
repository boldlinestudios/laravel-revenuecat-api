<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints\Concerns;

use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

trait Updatable
{
    abstract protected function client(): RevenueCatClient;

    abstract protected function basePath(): string;

    /**
     * Update a resource by id.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(string $id, array $data): Response
    {
        $id = rawurlencode($id);

        return $this->client()->post($this->basePath()."/{$id}", $data);
    }
}
