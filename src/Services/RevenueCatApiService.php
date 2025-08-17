<?php

namespace BoldlineStudios\RevenueCatApi\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class RevenueCatApiService
{
    protected PendingRequest $http;
    protected string $apiKey;
    protected string $baseUrl;
    protected int $timeout;

    public function __construct(string $apiKey, string $baseUrl, int $timeout = 30)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
        $this->timeout = $timeout;
        
        $this->http = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->timeout($this->timeout);
    }

    /**
     * Get subscriber information
     */
    public function getSubscriber(string $appUserId): Response
    {
        return $this->http->get("{$this->baseUrl}/v2/subscribers/{$appUserId}");
    }

    /**
     * Get subscriber entitlements
     */
    public function getSubscriberEntitlements(string $appUserId): Response
    {
        return $this->http->get("{$this->baseUrl}/v2/subscribers/{$appUserId}/entitlements");
    }

    /**
     * Grant promotional entitlement
     */
    public function grantPromotionalEntitlement(string $appUserId, array $data): Response
    {
        return $this->http->post("{$this->baseUrl}/v2/subscribers/{$appUserId}/entitlements", $data);
    }

    /**
     * Revoke promotional entitlement
     */
    public function revokePromotionalEntitlement(string $appUserId, string $entitlementId): Response
    {
        return $this->http->delete("{$this->baseUrl}/v2/subscribers/{$appUserId}/entitlements/{$entitlementId}");
    }

    /**
     * Get products
     */
    public function getProducts(): Response
    {
        return $this->http->get("{$this->baseUrl}/v2/products");
    }

    /**
     * Get offerings
     */
    public function getOfferings(): Response
    {
        return $this->http->get("{$this->baseUrl}/v2/offerings");
    }

    /**
     * Create a custom HTTP request
     */
    public function request(string $method, string $endpoint, array $data = []): Response
    {
        $url = $this->baseUrl . $endpoint;
        
        return match (strtoupper($method)) {
            'GET' => $this->http->get($url, $data),
            'POST' => $this->http->post($url, $data),
            'PUT' => $this->http->put($url, $data),
            'PATCH' => $this->http->patch($url, $data),
            'DELETE' => $this->http->delete($url, $data),
            default => throw new \InvalidArgumentException("Unsupported HTTP method: {$method}")
        };
    }
}
