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
        $packageId = rawurlencode($packageId);
        $path = "/packages/{$packageId}/products";

        /** @var ListPage<ProductData> */
        return $this->listPageForPath($path, ProductData::class, $limit, $startingAfter, $extra);
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
}
