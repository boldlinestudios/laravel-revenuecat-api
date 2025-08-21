<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

class App
{
    public function __construct(private RevenueCatClient $client) {}

    public function listOfPublicKeys(string $appId): Response
    {
        $appId = rawurlencode($appId);

        return $this->client->get("/apps/{$appId}/public_api_keys");
    }

    /**
     * List apps
     *
     * @param  array<string, mixed>  $query
     */
    public function list(array $query = []): Response
    {
        return $this->client->get('/apps', $query);
    }

    /**
     * Create an app
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Response
    {
        return $this->client->post('/apps', $data);
    }

    /**
     * Get a specific app
     */
    public function get(string $appId): Response
    {
        $appId = rawurlencode($appId);

        return $this->client->get("/apps/{$appId}");
    }

    /**
     * Update an app
     *
     * @param  array<string, mixed>  $data
     */
    public function update(string $appId, array $data): Response
    {
        $appId = rawurlencode($appId);

        // Using POST here based on docs; API may accept PATCH/PUT
        return $this->client->post("/apps/{$appId}", $data);
    }

    /**
     * Delete an app
     */
    public function delete(string $appId): Response
    {
        return $this->client->delete("/apps/{$appId}");
    }

    /**
     * Get the StoreKit config for an app
     */
    public function storeKitConfig(string $appId): Response
    {
        return $this->client->get("/apps/{$appId}/store_kit_config");
    }
}
