<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

class Package
{
    public function __construct(private RevenueCatClient $client) {}

    /**
     * List packages
     *
     * @param  array<string, mixed>  $query
     */
    public function list(array $query = []): Response
    {
        return $this->client->get('/packages', $query);
    }

    /**
     * Create a package
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Response
    {
        return $this->client->post('/packages', $data);
    }

    /**
     * Get a specific package
     */
    public function get(string $packageId): Response
    {
        $packageId = rawurlencode($packageId);

        return $this->client->get("/packages/{$packageId}");
    }

    /**
     * Update a package
     *
     * @param  array<string, mixed>  $data
     */
    public function update(string $packageId, array $data): Response
    {
        $packageId = rawurlencode($packageId);

        return $this->client->post("/packages/{$packageId}", $data);
    }

    /**
     * Delete a package
     */
    public function delete(string $packageId): Response
    {
        $packageId = rawurlencode($packageId);

        return $this->client->delete("/packages/{$packageId}");
    }

    /**
     * Get a list of products attached to a given package of an offering
     */
    public function listOfProducts(string $packageId): Response
    {
        $packageId = rawurlencode($packageId);

        return $this->client->get("/packages/{$packageId}/products");
    }
}
