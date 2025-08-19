<?php

namespace BoldlineStudios\RevenueCatApi;

use BoldlineStudios\RevenueCatApi\Endpoints\App as AppEndpoint;
use BoldlineStudios\RevenueCatApi\Endpoints\Customer;
use BoldlineStudios\RevenueCatApi\Endpoints\Entitlement;
use BoldlineStudios\RevenueCatApi\Endpoints\Offering;
use BoldlineStudios\RevenueCatApi\Endpoints\Package;
use BoldlineStudios\RevenueCatApi\Endpoints\Product;
use BoldlineStudios\RevenueCatApi\Endpoints\Project;
use BoldlineStudios\RevenueCatApi\Endpoints\Purchase;
use BoldlineStudios\RevenueCatApi\Endpoints\Subscription;
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

    public function offerings(): Offering
    {
        return new Offering($this->client);
    }

    public function packages(): Package
    {
        return new Package($this->client);
    }

    public function products(): Product
    {
        return new Product($this->client);
    }

    public function purchases(): Purchase
    {
        return new Purchase($this->client);
    }

    public function projects(): Project
    {
        return new Project($this->client);
    }

    public function subscriptions(): Subscription
    {
        return new Subscription($this->client);
    }
}
