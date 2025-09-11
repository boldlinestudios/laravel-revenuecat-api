<?php

namespace BoldLineStudios\RevenueCatApi\Endpoints;

use BoldLineStudios\RevenueCatApi\Data\EntitlementData;
use BoldLineStudios\RevenueCatApi\Data\ListPage;
use BoldLineStudios\RevenueCatApi\Data\PurchaseData;
use BoldLineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldLineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldLineStudios\RevenueCatApi\Http\RevenueCatClient;

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

    /**
     * Search for a one-time purchases by any of its associated store_purchase_identifier values
     * For example, this may include the transactionId of any transaction in an Apple App Store purchase,
     * or any order ID from a Google Play Store purchase.
     *
     * @return ListPage<PurchaseData>
     */
    public function searchPurchasesByIdentifier(string $storePurchaseIdentifier): ListPage
    {
        $storePurchaseIdentifier = rawurlencode($storePurchaseIdentifier);
        $path = "/purchases/search?store_purchase_identifier={$storePurchaseIdentifier}";

        /** @var ListPage<PurchaseData> */
        return $this->listPageForPath($path, PurchaseData::class);
    }
}
