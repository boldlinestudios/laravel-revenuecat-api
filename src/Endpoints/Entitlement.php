<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Data\EntitlementData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\ProductData;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Creatable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Deletable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Updatable;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;

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
     */
    public function create(string $lookupKey, string $displayName): EntitlementData
    {
        $data = [
            'lookup_key' => $lookupKey,
            'display_name' => $displayName,
        ];

        return EntitlementData::fromResponse($this->createRaw($data));
    }

    public function get(string $entitlementId): EntitlementData
    {
        return EntitlementData::fromResponse($this->getRaw($entitlementId));
    }

    /**
     * Update an entitlement.
     */
    public function update(string $entitlementId, string $displayName): EntitlementData
    {
        // this is all the data the api docs say is updatable 8/26/25
        $data = [
            'display_name' => $displayName,
        ];

        return EntitlementData::fromResponse($this->updateRaw($entitlementId, $data));
    }

    /**
     * @return ListPage<ProductData>
     */
    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<ProductData>
     */
    public function listOfProducts(
        string $entitlementId,
        int $limit = 20,
        ?string $startingAfter = null,
        array $extra = []
    ): ListPage {
        $entitlementId = rawurlencode($entitlementId);
        $path = "/entitlements/{$entitlementId}/products";

        /** @var ListPage<ProductData> */
        return $this->listPageForPath($path, ProductData::class, $limit, $startingAfter, $extra);
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<EntitlementData>
     */
    public function all(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        /** @var ListPage<EntitlementData> */
        return $this->listAsDto(EntitlementData::class, $limit, $startingAfter, $extra);
    }

    /**
     * @param  array<string>  $productIds
     */
    public function attachProducts(string $entitlementId, array $productIds): EntitlementData
    {
        $entitlementId = rawurlencode($entitlementId);
        $path = "/entitlements/{$entitlementId}/attach_products";

        return EntitlementData::fromResponse($this->client->post($path, ['product_ids' => $productIds]));
    }

    /**
     * @param  array<string>  $productIds
     */
    public function detachProducts(string $entitlementId, array $productIds): EntitlementData
    {
        $entitlementId = rawurlencode($entitlementId);
        $path = "/entitlements/{$entitlementId}/detach_products";

        return EntitlementData::fromResponse($this->client->post($path, ['product_ids' => $productIds]));
    }
}
