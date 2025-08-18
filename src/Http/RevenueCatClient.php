<?php

namespace BoldlineStudios\RevenueCatApi\Http;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class RevenueCatClient
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

    private function normalizePath(string $path): string
    {
        return str_starts_with($path, '/') ? $path : '/'.$path;
    }

    private function prefixProject(string $path): string
    {
        return $this->baseUrl.'/projects/'.$this->projectId.$this->normalizePath($path);
    }
}
