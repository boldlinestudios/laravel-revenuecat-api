<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Data\SubscriptionData;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

class Subscription
{
    use Retrievable;

    public function __construct(private RevenueCatClient $client) {}

    protected function client(): RevenueCatClient
    {
        return $this->client;
    }

    protected function basePath(): string
    {
        return '/subscriptions';
    }

    public function get(string $subscriptionId): SubscriptionData
    {
        return SubscriptionData::fromResponse($this->getRaw($subscriptionId));
    }

    /**
     * Get a list of entitlements associated with a subscription
     * This endpoint requires the following permission(s): customer_information:subscriptions:read
     */
    public function listOfEntitlements(string $subscriptionId): Response
    {
        $subscriptionId = rawurlencode($subscriptionId);

        return $this->client->get("/subscriptions/{$subscriptionId}/entitlements");
    }

    /**
     * Get a Play Store subscription's transactions
     * This endpoint requires the following permission(s): customer_information:subscriptions:read
     */
    public function listOfTransactions(string $subscriptionId): Response
    {
        $subscriptionId = rawurlencode($subscriptionId);

        return $this->client->get("/subscriptions/{$subscriptionId}/transactions");
    }

    /**
     * Get a secure, single-use URL that allows customers to access their Web Billing customer portal.
     * This endpoint requires the following permission(s): customer_information:subscriptions:read
     */
    public function getCustomerPortalUrl(string $subscriptionId): Response
    {
        $subscriptionId = rawurlencode($subscriptionId);

        return $this->client->get("/subscriptions/{$subscriptionId}/authenticated_management_url");
    }

    /**
     * Cancel an active Web Billing subscription. The customer will lose access
     * to the associated entitlements at the end of the current period.
     * This endpoint requires the following permission(s): customer_information:subscriptions:read_write
     */
    public function cancelWebBillingSubscription(string $subscriptionId): Response
    {
        $subscriptionId = rawurlencode($subscriptionId);

        return $this->client->post("/subscriptions/{$subscriptionId}/actions/cancel");
    }

    /**
     * Cancel a Web Billing subscription by refunding the most recent payment.
     * The customer will immediately lose access to the associated entitlements.
     * This endpoint requires the following permission(s): customer_information:subscriptions:read_write
     */
    public function refundWebBillingSubscription(string $subscriptionId): Response
    {
        $subscriptionId = rawurlencode($subscriptionId);

        return $this->client->post("/subscriptions/{$subscriptionId}/actions/refund");
    }
}
