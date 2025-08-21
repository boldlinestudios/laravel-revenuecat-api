<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints\Concerns;

use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

trait Deletable
{
    abstract protected function client(): RevenueCatClient;

    abstract protected function basePath(): string;

    /**
     * Delete a resource by id.
     */
    public function delete(string $id): Response
    {
        $id = rawurlencode($id);

        return $this->client()->delete($this->basePath()."/{$id}");
    }
}
