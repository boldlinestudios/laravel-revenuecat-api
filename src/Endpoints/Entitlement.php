<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Data\EntitlementData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Creatable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Deletable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Updatable;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

class Entitlement
{
    use Creatable;
    use Deletable;
    use Listable;
    use Retrievable;
    use Updatable;

    public function __construct(private RevenueCatClient $client) {}

    protected function client(): RevenueCatClient
    {
        return $this->client;
    }

    protected function basePath(): string
    {
        return '/entitlements';
    }

    /**
     * Create an entitlement.
     *
     * @param array{
     *   lookup_key: string,
     *   display_name: string,
     * } $data Entitlement payload with lookup key and display name.
     */
    public function create(array $data): EntitlementData
    {
        return EntitlementData::fromResponse($this->createRaw($data));
    }

    public function get(string $entitlementId): EntitlementData
    {
        return EntitlementData::fromResponse($this->getRaw($entitlementId));
    }

    /**
     * Update an entitlement.
     *
     * @param array{
     *   display_name: string,
     * } $data Entitlement payload with display name.
     */
    public function update(string $entitlementId, array $data): EntitlementData
    {
        return EntitlementData::fromResponse($this->updateRaw($entitlementId, $data));
    }

    /**
     * Get a list of products attached to a given entitlement
     */
    public function listOfProducts(string $entitlementId): Response
    {
        $entitlementId = rawurlencode($entitlementId);

        return $this->client->get("/entitlements/{$entitlementId}/products");
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<EntitlementData>
     */
    public function list(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->listAsDto(EntitlementData::class, $limit, $startingAfter, $extra);
    }
}
