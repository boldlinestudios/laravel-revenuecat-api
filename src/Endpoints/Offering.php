<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\OfferingData;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Creatable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Deletable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Updatable;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;

class Offering
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
        return '/offerings';
    }

    /**
     * @param array{
     *   lookup_key: string,
     *   display_name: string,
     *   metadata?: array<string, mixed>
     * } $data Offering payload with lookup key, display name, and metadata.
     */
    public function create(array $data): OfferingData
    {
        return OfferingData::fromResponse($this->createRaw($data));
    }

    public function get(string $offeringId): OfferingData
    {
        return OfferingData::fromResponse($this->getRaw($offeringId));
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<OfferingData>
     */
    public function list(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->listAsDto(OfferingData::class, $limit, $startingAfter, $extra);
    }

    /**
     * @param array{
     *   lookup_key: string,
     *   display_name: string,
     *   metadata?: array<string, mixed>
     * } $data Offering payload with lookup key, display name, and metadata.
     */
    public function update(string $offeringId, array $data): OfferingData
    {
        return OfferingData::fromResponse($this->updateRaw($offeringId, $data));
    }
}
