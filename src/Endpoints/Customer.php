<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Creatable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Deletable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

class Customer
{
    use Creatable;
    use Deletable;
    use Listable;
    use Retrievable;

    public function __construct(private RevenueCatClient $client) {}

    protected function client(): RevenueCatClient
    {
        return $this->client;
    }

    protected function basePath(): string
    {
        return '/customers';
    }

    /**
     * Get a list of subscriptions associated with a customer
     */
    public function listOfSubscriptions(string $customerId): Response
    {
        $customerId = rawurlencode($customerId);

        return $this->client->get("customers/{$customerId}/subscriptions");
    }

    /**
     * Get a list of purchases associated with a customer
     */
    public function listOfPurchases(string $customerId): Response
    {
        $customerId = rawurlencode($customerId);

        return $this->client->get("customers/{$customerId}/purchases");
    }

    /**
     * Get a list of active entitlements for a customer
     */
    public function listOfActiveEntitlements(string $customerId): Response
    {
        $customerId = rawurlencode($customerId);

        return $this->client->get("customers/{$customerId}/active_entitlements");
    }

    /**
     * Get a list of aliases for a customer
     */
    public function listOfAliases(string $customerId): Response
    {
        $customerId = rawurlencode($customerId);

        return $this->client->get("customers/{$customerId}/aliases");
    }

    /**
     * Get a list of virtual currency balances for the customer
     */
    public function listOfVirtualCurrencyBalances(string $customerId): Response
    {
        $customerId = rawurlencode($customerId);

        return $this->client->get("customers/{$customerId}/virtual_currencies");
    }

    /**
     * Get a list of customer attributes
     */
    public function listOfAttributes(string $customerId): Response
    {
        $customerId = rawurlencode($customerId);

        return $this->client->get("customers/{$customerId}/attributes");
    }
}
