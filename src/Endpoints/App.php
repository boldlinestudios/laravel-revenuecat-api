<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Creatable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Deletable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Updatable;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

class App
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
        return '/apps';
    }

    public function listOfPublicKeys(string $appId): Response
    {
        $appId = rawurlencode($appId);

        return $this->client->get("/apps/{$appId}/public_api_keys");
    }

    /**
     * Get the StoreKit config for an app
     */
    public function storeKitConfig(string $appId): Response
    {
        return $this->client->get("/apps/{$appId}/store_kit_config");
    }
}
