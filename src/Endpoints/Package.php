<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\PackageData;
use BoldlineStudios\RevenueCatApi\Data\ProductData;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Creatable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Deletable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Updatable;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;

class Package
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
        return '/packages';
    }

    /**
     * Create a package.
     */
    public function create(string $lookupKey, string $displayName, ?int $position = null): PackageData
    {
        $data = [
            'lookup_key' => $lookupKey,
            'display_name' => $displayName,
        ];
        if ($position) {
            $data['position'] = $position;
        }

        return PackageData::fromResponse($this->createRaw($data));
    }

    public function get(string $appId): PackageData
    {
        return PackageData::fromResponse($this->getRaw($appId));
    }

    /**
     * Update a package.
     *
     * Note: any null values will be ignored and not updated
     */
    public function update(string $id, ?string $displayName, ?int $position = null): PackageData
    {
        $data = [];
        if ($displayName) {
            $data['display_name'] = $displayName;
        }
        if ($position) {
            $data['position'] = $position;
        }

        return PackageData::fromResponse($this->updateRaw($id, $data));
    }

    /**
     * Get a list of products attached to a given package of an offering
     *
     * @param  array<string, mixed>  $extra
     * @return ListPage<ProductData>
     */
    public function listOfProducts(string $packageId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        // This endpoint returns items shaped as { product: {...}, eligibility_criteria: ... }
        // This is unlike other endpoints that return the items at the top level.

        $packageId = rawurlencode($packageId);
        $path = "/packages/{$packageId}/products";

        // We unwrap the inner "product" payloads into ProductData DTOs while preserving
        // standard pagination fields.
        $response = $this->listRawForPath($path, $limit, $startingAfter, $extra);
        $payload = $response->json();
        $payload = is_array($payload) ? $payload : [];

        $items = $payload['items'] ?? [];
        $items = is_array($items) ? $items : [];

        /** @var array<int, ProductData> $dtos */
        $dtos = [];
        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            // Preferred: nested under 'product'
            if (isset($item['product']) && is_array($item['product'])) {
                /** @var array<string, mixed> $product */
                $product = $item['product'];
                $dtos[] = ProductData::fromArray($product);
            }
        }

        $next = isset($payload['next_page']) && is_string($payload['next_page']) ? $payload['next_page'] : null;
        $url = isset($payload['url']) && is_string($payload['url']) ? $payload['url'] : $path;

        return new ListPage($dtos, $next, $url, $response);

    }

    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<PackageData>
     */
    public function list(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        /** @var ListPage<PackageData> */
        return $this->listAsDto(PackageData::class, $limit, $startingAfter, $extra);
    }

    /**
     * Attach a set of products to a package.
     *
     * @param  list<array{product_id: string, eligibility_criteria: string}>  $productAssociationList
     */
    public function attachProducts(string $packageId, array $productAssociationList): PackageData
    {
        $packageId = rawurlencode($packageId);
        $path = "/packages/{$packageId}/actions/attach_products";

        return PackageData::fromResponse($this->client->post($path, ['products' => $productAssociationList]));
    }

    /**
     * Detach a set of products from a package.
     *
     * @param  array<string>  $productIds
     */
    public function detachProducts(string $packageId, array $productIds): PackageData
    {
        $packageId = rawurlencode($packageId);
        $path = "/packages/{$packageId}/actions/detach_products";

        return PackageData::fromResponse($this->client->post($path, ['product_ids' => $productIds]));
    }
}
