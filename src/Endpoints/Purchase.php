<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Data\EntitlementData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\PurchaseData;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;

class Purchase
{
    use Listable;
    use Retrievable;

    public function __construct(private RevenueCatClient $client) {}

    protected function client(): RevenueCatClient
    {
        return $this->client;
    }

    protected function basePath(): string
    {
        return '/purchases';
    }

    public function get(string $purchaseId): PurchaseData
    {
        return PurchaseData::fromResponse($this->getRaw($purchaseId));
    }

    /**
     * Get a list of entitlements associated with a purchase
     *
     * @param  array<string, mixed>  $extra
     * @return ListPage<EntitlementData>
     */
    public function listOfEntitlements(string $purchaseId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        $purchaseId = rawurlencode($purchaseId);
        $path = "/purchases/{$purchaseId}/entitlements";

        /** @var ListPage<EntitlementData> */
        return $this->listPageForPath($path, EntitlementData::class, $limit, $startingAfter, $extra);
    }

    public function refundWebBillingPurchase(string $purchaseId): PurchaseData
    {
        $purchaseId = rawurlencode($purchaseId);
        $path = "/purchases/{$purchaseId}/actions/refund";

        return PurchaseData::fromResponse($this->client->post($path));
    }
}
