<?php

namespace BoldLineStudios\RevenueCatApi\Endpoints;

use BoldLineStudios\RevenueCatApi\Data\ListPage;
use BoldLineStudios\RevenueCatApi\Data\OfferingData;
use BoldLineStudios\RevenueCatApi\Endpoints\Concerns\Creatable;
use BoldLineStudios\RevenueCatApi\Endpoints\Concerns\Deletable;
use BoldLineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldLineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldLineStudios\RevenueCatApi\Endpoints\Concerns\Updatable;
use BoldLineStudios\RevenueCatApi\Http\RevenueCatClient;

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
     * @param  array<string, mixed>|null  $metadata
     */
    public function create(string $lookupKey, string $displayName, ?array $metadata = null): OfferingData
    {
        $data = [
            'lookup_key' => $lookupKey,
            'display_name' => $displayName,
            'metadata' => $metadata, // color => blue, call_to_action => Get it now etc
        ];

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
    public function all(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        /** @var ListPage<OfferingData> */
        return $this->listAsDto(OfferingData::class, $limit, $startingAfter, $extra);
    }

    /**
     *  Update an offering.
     *
     * @param  array<string, mixed>|null  $metadata
     *
     * Note: any null values will be ignored and not updated
     */
    public function update(string $offeringId, ?string $displayName = null, ?bool $isCurrent = null, ?array $metadata = null): OfferingData
    {
        $data = [];
        if ($displayName) {
            $data['display_name'] = $displayName;
        }
        if ($isCurrent) {
            $data['is_current'] = $isCurrent;
        }
        if ($metadata) {
            $data['metadata'] = $metadata;
        }

        if (empty($data)) {
            throw new \InvalidArgumentException('No data to update for offering '.$offeringId);
        }

        return OfferingData::fromResponse($this->updateRaw($offeringId, $data));
    }
}
