<?php

namespace BoldlineStudios\RevenueCatApi\Http;

use BoldlineStudios\RevenueCatApi\Endpoints\App as Apps;
use BoldlineStudios\RevenueCatApi\Endpoints\Customer;
use BoldlineStudios\RevenueCatApi\Endpoints\Entitlement;
use BoldlineStudios\RevenueCatApi\Endpoints\Offering;
use BoldlineStudios\RevenueCatApi\Endpoints\Package;
use BoldlineStudios\RevenueCatApi\Endpoints\Product;
use BoldlineStudios\RevenueCatApi\Endpoints\Project;
use BoldlineStudios\RevenueCatApi\Endpoints\Purchase;
use BoldlineStudios\RevenueCatApi\Endpoints\Subscription;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class RevenueCatClient
{
    private PendingRequest $http;

    private string $baseUrl;

    private string $projectId;

    public function __construct(
        string $apiKey,
        string $baseUrl,
        string $projectId,
        int $timeout = 30,
    ) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->projectId = $projectId;

        $client = Http::withHeaders([
            'Authorization' => 'Bearer '.$apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->timeout($timeout);

        $this->http = $client;
    }

    public function get(string $path, array $query = []): Response
    {
        return $this->http->get($this->prefixProject($path), $query);
    }

    public function post(string $path, array $body = []): Response
    {
        return $this->http->post($this->prefixProject($path), $body);
    }

    public function delete(string $path, array $body = []): Response
    {
        return $this->http->delete($this->prefixProject($path), $body);
    }

    public function forProject(string $projectId): self
    {
        $clone = clone $this;
        $clone->projectId = $projectId;

        return $clone;
    }

    public function apps(): Apps
    {
        return new Apps($this);
    }

    public function customers(): Customer
    {
        return new Customer($this);
    }

    public function entitlements(): Entitlement
    {
        return new Entitlement($this);
    }

    public function offerings(): Offering
    {
        return new Offering($this);
    }

    public function packages(): Package
    {
        return new Package($this);
    }

    public function products(): Product
    {
        return new Product($this);
    }

    public function projects(): Project
    {
        return new Project($this);
    }

    public function purchases(): Purchase
    {
        return new Purchase($this);
    }

    public function subscriptions(): Subscription
    {
        return new Subscription($this);
    }

    private function normalizePath(string $path): string
    {
        return str_starts_with($path, '/') ? $path : '/'.$path;
    }

    private function prefixProject(string $path): string
    {
        // projects endpoint is not prefixed with the project id
        if (str_starts_with($path, '/projects')) {
            return $this->baseUrl.$this->normalizePath($path);
        }

        return $this->baseUrl.'/projects/'.$this->projectId.$this->normalizePath($path);
    }
}
