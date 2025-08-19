<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

class Purchase
{
    public function __construct(private RevenueCatClient $client) {}

    /**
     * Get a specific purchase
     */
    public function get(string $purchaseId): Response
    {
        $purchaseId = rawurlencode($purchaseId);

        return $this->client->get("/purchases/{$purchaseId}");
    }

    /**
     * Get a list of entitlements associated with a purchase
     */
    public function listOfEntitlements(string $purchaseId): Response
    {
        $purchaseId = rawurlencode($purchaseId);

        return $this->client->get("/purchases/{$purchaseId}/entitlements");
    }
}
