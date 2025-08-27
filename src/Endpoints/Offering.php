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
     * @param  array<string, mixed>|null  $metadata
     */
    public function create(string $lookupKey, string $displayName, ?array $metadata = []): OfferingData
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
    public function list(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->listAsDto(OfferingData::class, $limit, $startingAfter, $extra);
    }

    /**
     *  Update an offering.
     *
     * @param  array<string, mixed>|null  $metadata
     *
     * Note: any null values will be ignored and not updated
     */
    public function update(string $offeringId, ?string $displayName, ?bool $isCurrent, ?array $metadata = []): OfferingData
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

        return OfferingData::fromResponse($this->updateRaw($offeringId, $data));
    }
}
