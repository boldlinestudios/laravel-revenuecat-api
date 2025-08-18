<?php

namespace BoldlineStudios\RevenueCatApi\Http;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class RevenueCatClient
{
    private PendingRequest $http;

    private string $baseUrl;

    public function __construct(
        string $apiKey,
        string $baseUrl,
        int $timeout = 30,
    ) {
        $this->baseUrl = rtrim($baseUrl, '/');

        $client = Http::withHeaders([
            'Authorization' => 'Bearer '.$apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->timeout($timeout);

        $this->http = $client;
    }

    public function get(string $path, array $query = []): Response
    {
        return $this->http->get($this->baseUrl.$this->normalizePath($path), $query);
    }

    public function post(string $path, array $body = []): Response
    {
        return $this->http->post($this->baseUrl.$this->normalizePath($path), $body);
    }

    public function delete(string $path, array $body = []): Response
    {
        return $this->http->delete($this->baseUrl.$this->normalizePath($path), $body);
    }

    private function normalizePath(string $path): string
    {
        return str_starts_with($path, '/') ? $path : '/'.$path;
    }
}
