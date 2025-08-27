<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\PackageData;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Creatable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Deletable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Updatable;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

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
     * @param array{
     *   display_name: string,
     *   position: int,
     * } $data Package payload with display name and position.
     */
    public function update(string $id, array $data): PackageData
    {
        return PackageData::fromResponse($this->updateRaw($id, $data));
    }

    /**
     * Get a list of products attached to a given package of an offering
     */
    public function listOfProducts(string $packageId): Response
    {
        $packageId = rawurlencode($packageId);

        return $this->client->get("/packages/{$packageId}/products");
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<PackageData>
     */
    public function list(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->listAsDto(PackageData::class, $limit, $startingAfter, $extra);
    }
}
