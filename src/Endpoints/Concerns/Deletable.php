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
    public function delete(string $id): bool
    {
        $response = $this->deleteRaw($id);
        $payload = $response->json();

        return is_array($payload)
            && array_key_exists('deleted_at', $payload)
            && is_numeric($payload['deleted_at']);
    }

    /**
     * Delete a resource by id (raw Response).
     */
    public function deleteRaw(string $id): Response
    {
        $id = rawurlencode($id);

        return $this->client()->delete($this->basePath()."/{$id}");
    }
}
