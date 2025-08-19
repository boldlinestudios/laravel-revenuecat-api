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
use BoldlineStudios\RevenueCatApi\Exceptions\ApiResponseException;
use BoldlineStudios\RevenueCatApi\Exceptions\AuthenticationException;
use BoldlineStudios\RevenueCatApi\Exceptions\AuthorizationException;
use BoldlineStudios\RevenueCatApi\Exceptions\BadRequestException;
use BoldlineStudios\RevenueCatApi\Exceptions\ConflictException;
use BoldlineStudios\RevenueCatApi\Exceptions\NotFoundException;
use BoldlineStudios\RevenueCatApi\Exceptions\RateLimitException;
use BoldlineStudios\RevenueCatApi\Exceptions\ServerErrorException;
use BoldlineStudios\RevenueCatApi\Exceptions\ValidationException;
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
        $response = $this->http->get($this->prefixProject($path), $query);

        return $this->handleResponse($response);
    }

    public function post(string $path, array $body = []): Response
    {
        $response = $this->http->post($this->prefixProject($path), $body);

        return $this->handleResponse($response);
    }

    public function delete(string $path, array $body = []): Response
    {
        $response = $this->http->delete($this->prefixProject($path), $body);

        return $this->handleResponse($response);
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

    private function handleResponse(Response $response): Response
    {
        $status = $response->status();

        if ($status >= 200 && $status < 300) {
            return $response;
        }

        $payload = $response->json();
        $payload = is_array($payload) ? $payload : [];

        $message = $payload['message']
            ?? ($payload['error']['message'] ?? 'RevenueCat API error');
        $errorCode = $payload['code']
            ?? ($payload['error']['code'] ?? null);
        $errorType = $payload['type']
            ?? ($payload['error']['type'] ?? null);

        // Rate limit details (if present)
        if ($status === 429) {
            $limitHeader = $response->header('X-RateLimit-Limit');
            $remainingHeader = $response->header('X-RateLimit-Remaining');
            $resetHeader = $response->header('X-RateLimit-Reset');

            $limit = is_numeric($limitHeader) ? (int) $limitHeader : null;
            $remaining = is_numeric($remainingHeader) ? (int) $remainingHeader : null;
            $reset = is_numeric($resetHeader) ? (int) $resetHeader : null;

            throw new RateLimitException(
                $message,
                $status,
                $limit,
                $remaining,
                $reset,
                $errorCode,
                $errorType,
                null,
                $payload,
            );
        }

        switch ($status) {
            case 400:
                throw new BadRequestException($message, $status, $errorCode, $errorType, null, $payload);
            case 401:
                throw new AuthenticationException($message, $status, $errorCode, $errorType, null, $payload);
            case 403:
                throw new AuthorizationException($message, $status, $errorCode, $errorType, null, $payload);
            case 404:
                throw new NotFoundException($message, $status, $errorCode, $errorType, null, $payload);
            case 409:
                throw new ConflictException($message, $status, $errorCode, $errorType, null, $payload);
            case 422:
                throw new ValidationException($message, $status, $errorCode, $errorType, null, $payload);
            default:
                if ($status >= 500 && $status < 600) {
                    throw new ServerErrorException($message, $status, $errorCode, $errorType, null, $payload);
                }

                throw new ApiResponseException($message, $status, $errorCode, $errorType, null, $payload);
        }
    }
}
