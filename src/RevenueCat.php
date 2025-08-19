<?php

namespace BoldlineStudios\RevenueCatApi;

use BoldlineStudios\RevenueCatApi\Endpoints\App as AppEndpoint;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;

final class RevenueCat
{
    public function __construct(private RevenueCatClient $client) {}

    public function apps(): AppEndpoint
    {
        return new AppEndpoint($this->client);
    }
}
