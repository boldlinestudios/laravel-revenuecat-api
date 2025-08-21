<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Creatable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Deletable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Updatable;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

class Package
{
    use Creatable;
    use Deletable;
    use Listable;
    use Retrievable;
    use Updatable;

    public function __construct(private RevenueCatClient $client) {}

    protected function client(): RevenueCatClient
    {
        return $this->client;
    }

    protected function basePath(): string
    {
        return '/packages';
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
