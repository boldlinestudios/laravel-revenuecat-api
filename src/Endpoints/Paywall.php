<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Data\PaywallData;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Creatable;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;

class Paywall
{
    use Creatable;

    public function __construct(private RevenueCatClient $client) {}

    protected function client(): RevenueCatClient
    {
        return $this->client;
    }

    protected function basePath(): string
    {
        return '/paywalls';
    }

    /**
     * Create a paywall for an offering of the project
     */
    public function create(string $offeringId): PaywallData
    {
        $data = [
            'offering_id' => $offeringId,
        ];

        return PaywallData::fromResponse($this->createRaw($data));
    }
}
