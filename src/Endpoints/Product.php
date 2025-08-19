<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

class Product
{
    public function __construct(private RevenueCatClient $client) {}

    /**
     * List products
     */
    public function list(array $query = []): Response
    {
        return $this->client->get('/products', $query);
    }

    /**
     * Create a product
     */
    public function create(array $data): Response
    {
        return $this->client->post('/products', $data);
    }

    /**
     * Get a product
     */
    public function get(string $productId): Response
    {
        $productId = rawurlencode($productId);

        return $this->client->get("/products/{$productId}");
    }

    /**
     * Update a product
     */
    public function update(string $productId, array $data): Response
    {
        $productId = rawurlencode($productId);

        return $this->client->post("/products/{$productId}", $data);
    }

    public function delete(string $productId): Response
    {
        $productId = rawurlencode($productId);

        return $this->client->delete("/products/{$productId}");
    }
}
