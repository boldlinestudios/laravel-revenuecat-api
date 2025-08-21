<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

class Customer
{
    public function __construct(private RevenueCatClient $client) {}

    /**
     * List customers
     *
     * @param  array<string, mixed>  $query
     */
    public function list(array $query = []): Response
    {
        return $this->client->get('/customers', $query);
    }

    /**
     * Get a specific customer
     */
    public function get(string $customerId): Response
    {
        $customerId = rawurlencode($customerId);

        return $this->client->get("/customers/{$customerId}");
    }

    /**
     * Create a customer
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Response
    {
        return $this->client->post('/customers', $data);
    }

    /**
     * Update a customer
     *
     * @param  array<string, mixed>  $data
     */
    public function update(string $customerId, array $data): Response
    {
        $customerId = rawurlencode($customerId);

        return $this->client->post("/customers/{$customerId}", $data);
    }

    /**
     * Delete a customer
     */
    public function delete(string $customerId): Response
    {
        $customerId = rawurlencode($customerId);

        return $this->client->delete("/customers/{$customerId}");
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
