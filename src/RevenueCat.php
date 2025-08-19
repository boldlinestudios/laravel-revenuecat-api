<?php

namespace BoldlineStudios\RevenueCatApi;

use BoldlineStudios\RevenueCatApi\Endpoints\App as AppEndpoint;
use BoldlineStudios\RevenueCatApi\Endpoints\Customer;
use BoldlineStudios\RevenueCatApi\Endpoints\Entitlement;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;

final class RevenueCat
{
    public function __construct(private RevenueCatClient $client) {}

    public function apps(): AppEndpoint
    {
        return new AppEndpoint($this->client);
    }

    public function customers(): Customer
    {
        return new Customer($this->client);
    }

    public function entitlements(): Entitlement
    {
        return new Entitlement($this->client);
    }
}
