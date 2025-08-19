<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

class Offering
{
    public function __construct(private RevenueCatClient $client) {}

    /**
     * List offerings
     */
    public function list(array $query = []): Response
    {
        return $this->client->get('/offerings', $query);
    }

    /**
     * Create an offering
     */
    public function create(array $data): Response
    {
        return $this->client->post('/offerings', $data);
    }

    /**
     * Get a specific offering
     */
    public function get(string $offeringId): Response
    {
        $offeringId = rawurlencode($offeringId);

        return $this->client->get("/offerings/{$offeringId}");
    }

    /**
     * Update an offering
     */
    public function update(string $offeringId, array $data): Response
    {
        $offeringId = rawurlencode($offeringId);

        return $this->client->post("/offerings/{$offeringId}", $data);
    }

    /**
     * Delete an offering
     */
    public function delete(string $offeringId): Response
    {
        $offeringId = rawurlencode($offeringId);

        return $this->client->delete("/offerings/{$offeringId}");
    }
}
