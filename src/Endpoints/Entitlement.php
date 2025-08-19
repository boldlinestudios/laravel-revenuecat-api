<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

class Entitlement
{
    public function __construct(private RevenueCatClient $client) {}

    /**
     * List entitlements
     */
    public function list(array $query = []): Response
    {
        return $this->client->get('/entitlements', $query);
    }

    /**
     * Create an entitlement
     */
    public function create(array $data): Response
    {
        return $this->client->post('/entitlements', $data);
    }

    /**
     * Get a specific entitlement
     */
    public function get(string $entitlementId): Response
    {
        $entitlementId = rawurlencode($entitlementId);

        return $this->client->get("/entitlements/{$entitlementId}");
    }

    /**
     * Update an entitlement
     */
    public function update(string $entitlementId, array $data): Response
    {
        $entitlementId = rawurlencode($entitlementId);

        return $this->client->post("/entitlements/{$entitlementId}", $data);
    }

    /**
     * Delete an entitlement
     */
    public function delete(string $entitlementId): Response
    {
        $entitlementId = rawurlencode($entitlementId);

        return $this->client->delete("/entitlements/{$entitlementId}");
    }

    /**
     * Get a list of products attached to a given entitlement
     */
    public function listOfProducts(string $entitlementId): Response
    {
        $entitlementId = rawurlencode($entitlementId);

        return $this->client->get("/entitlements/{$entitlementId}/products");
    }
}
