<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

class Purchase
{
    use Retrievable;

    public function __construct(private RevenueCatClient $client) {}

    protected function client(): RevenueCatClient
    {
        return $this->client;
    }

    protected function basePath(): string
    {
        return '/purchases';
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
