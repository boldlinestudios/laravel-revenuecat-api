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
use BoldlineStudios\RevenueCatApi\Http\Concerns\ConvenienceMethods;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class RevenueCatClient
{
    use ConvenienceMethods;

    private PendingRequest $http;

    public function __construct(
        string $apiKey,
        private string $baseUrl,
        private string $projectId,
        int $timeout = 30,
    ) {
        $this->baseUrl = rtrim($this->baseUrl, '/');

        $client = Http::withHeaders([
            'Authorization' => 'Bearer '.$apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->timeout($timeout);

        $this->http = $client;
    }

    /**
     * @param  array<string, mixed>  $query
     */
    public function get(string $path, array $query = []): Response
    {
        $response = $this->http->get($this->prefixProject($path), $query);

        return $this->handleResponse($response);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    public function post(string $path, array $body = []): Response
    {
        $response = $this->http->post($this->prefixProject($path), $body);

        return $this->handleResponse($response);
    }

    /**
     * @param  array<string, mixed>  $body
     */
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

        $this->handleErrorResponse($response);
    }

    private function handleErrorResponse(Response $response): never
    {
        $status = $response->status();

        $payload = $response->json();
        /** @var array<string, mixed> $payload */
        $payload = is_array($payload) ? $payload : [];

        $error = $payload['error'] ?? null;
        /** @var array<string, mixed> $error */
        $error = is_array($error) ? $error : [];

        $message = $payload['message'] ?? ($error['message'] ?? 'RevenueCat API error');
        if (! is_string($message) || $message === '') {
            $message = 'RevenueCat API error';
        }

        $errorCode = $payload['code'] ?? ($error['code'] ?? null);
        if (! is_string($errorCode)) {
            $errorCode = null;
        }

        $errorType = $payload['type'] ?? ($error['type'] ?? null);
        if (! is_string($errorType)) {
            $errorType = null;
        }

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

        $exceptionClass = match ($status) {
            400 => BadRequestException::class,
            401 => AuthenticationException::class,
            403 => AuthorizationException::class,
            404 => NotFoundException::class,
            409 => ConflictException::class,
            422 => ValidationException::class,
            500, 502, 503, 504 => ServerErrorException::class,
            default => ApiResponseException::class,
        };

        throw new $exceptionClass($message, $status, $errorCode, $errorType, null, $payload);
    }
}
