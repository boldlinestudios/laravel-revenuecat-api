<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

class Project
{
    public function __construct(private RevenueCatClient $client) {}

    /**
     * List projects
     */
    public function list(array $query = []): Response
    {
        return $this->client->get('/projects', $query);
    }
}
