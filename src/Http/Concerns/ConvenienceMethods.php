<?php

namespace BoldlineStudios\RevenueCatApi\Http\Concerns;

use BoldlineStudios\RevenueCatApi\Data\AppData;
use BoldlineStudios\RevenueCatApi\Data\CustomerData;
use BoldlineStudios\RevenueCatApi\Data\EntitlementData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\OfferingData;
use BoldlineStudios\RevenueCatApi\Data\PackageData;
use BoldlineStudios\RevenueCatApi\Data\ProductData;
use BoldlineStudios\RevenueCatApi\Data\ProjectData;
use BoldlineStudios\RevenueCatApi\Data\PurchaseData;
use BoldlineStudios\RevenueCatApi\Data\SubscriptionData;
use Illuminate\Http\Client\Response;

trait ConvenienceMethods
{
    // App convenience methods
    public function getApp(string $appId): AppData
    {
        return $this->apps()->get($appId);
    }

    /**
     * @param  array<string,mixed>  $extra
     * @return ListPage<AppData>
     */
    public function getAppList(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->apps()->list($limit, $startingAfter, $extra);
    }

    /**
     * Create an app.
     *
     * Provider-specific config lives under a key matching `type`.
     *
     * storeconfig top level must match the type. Example payload for $storeConfig:
     * [
     *   'play_store' => [
     *     'package_name' => 'com.example.app',
     *   ],
     * ]
     *
     * @param  array<string, array<string, mixed>>  $storeConfig
     */
    public function createApp(string $name, string $type, array $storeConfig): AppData
    {
        return $this->apps()->create($name, $type, $storeConfig);
    }

    /**
     * Update an app.
     *
     * @param  array<string, array<string, mixed>>  $storeConfig
     *
     * Example payload for $storeConfig:
     * [
     *   'play_store' => [
     *     'bundle_id' => 'com.example.app',
     *     'shared_secret' => '1234567890abcdef1234567890abcdef',
     *   ],
     * ]
     */
    public function updateApp(string $appId, ?string $name, array $storeConfig): AppData
    {
        return $this->apps()->update($appId, $name, $storeConfig);
    }

    public function deleteApp(string $appId): bool
    {
        return $this->apps()->delete($appId);
    }

    public function getAppStoreKitConfig(string $appId): Response
    {
        return $this->apps()->storeKitConfig($appId);
    }

    public function getAppPublicKeys(string $appId): Response
    {
        return $this->apps()->listOfPublicKeys($appId);
    }

    // Customer convenience methods
    public function getCustomer(string $customerId): CustomerData
    {
        return $this->customers()->get($customerId);
    }

    /**
     * @param  array<string,mixed>  $extra
     * @return ListPage<CustomerData>
     */
    public function getCustomerList(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->customers()->list($limit, $startingAfter, $extra);
    }

    /**
     * Create a customer.
     *
     * @param  list<array{name: string, value: string}>  $attributes
     */
    public function createCustomer(string $id, array $attributes): CustomerData
    {
        return $this->customers()->create($id, $attributes);
    }

    public function deleteCustomer(string $customerId): bool
    {
        return $this->customers()->delete($customerId);
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<SubscriptionData>
     */
    public function getCustomerSubscriptions(
        string $customerId,
        int $limit = 20,
        ?string $startingAfter = null,
        array $extra = []
    ): ListPage {
        return $this->customers()->listOfSubscriptions($customerId, $limit, $startingAfter, $extra);
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<PurchaseData>
     */
    public function getCustomerPurchases(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->customers()->listOfPurchases($customerId, $limit, $startingAfter, $extra);
    }

    public function getCustomerActiveEntitlements(string $customerId): Response
    {
        return $this->customers()->listOfActiveEntitlements($customerId);
    }

    public function getCustomerAliases(string $customerId): Response
    {
        return $this->customers()->listOfAliases($customerId);
    }

    public function getCustomerVirtualCurrencyBalances(string $customerId): Response
    {
        return $this->customers()->listOfVirtualCurrencyBalances($customerId);
    }

    public function getCustomerAttributes(string $customerId): Response
    {
        return $this->customers()->listOfAttributes($customerId);
    }

    // Entitlement convenience methods
    public function getEntitlement(string $entitlementId): EntitlementData
    {
        return $this->entitlements()->get($entitlementId);
    }

    /**
     * @param  array<string,mixed>  $extra
     * @return ListPage<EntitlementData>
     */
    public function getEntitlementList(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->entitlements()->list($limit, $startingAfter, $extra);
    }

    public function createEntitlement(string $lookupKey, string $displayName): EntitlementData
    {
        return $this->entitlements()->create($lookupKey, $displayName);
    }

    public function updateEntitlement(string $entitlementId, string $displayName): EntitlementData
    {
        return $this->entitlements()->update($entitlementId, $displayName);
    }

    public function deleteEntitlement(string $entitlementId): bool
    {
        return $this->entitlements()->delete($entitlementId);
    }

    /**
     * @return ListPage<ProductData>
     */
    public function getEntitlementProducts(string $entitlementId): ListPage
    {
        return $this->entitlements()->listOfProducts($entitlementId);
    }

    // Offering convenience methods
    public function getOffering(string $offeringId): OfferingData
    {
        return $this->offerings()->get($offeringId);
    }

    /**
     * @param  array<string,mixed>  $extra
     * @return ListPage<OfferingData>
     */
    public function getOfferingList(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->offerings()->list($limit, $startingAfter, $extra);
    }

    /**
     * @param  array<string, mixed>|null  $metadata
     */
    public function createOffering(string $lookupKey, string $displayName, ?array $metadata = []): OfferingData
    {
        return $this->offerings()->create($lookupKey, $displayName, $metadata);
    }

    /**
     * Update an offering.
     *
     * @param  array<string, mixed>|null  $metadata
     *
     * Note: any null values will be ignored and not updated
     */
    public function updateOffering(string $offeringId, ?string $displayName, ?bool $isCurrent, ?array $metadata = []): OfferingData
    {
        return $this->offerings()->update($offeringId, $displayName, $isCurrent, $metadata);
    }

    public function deleteOffering(string $offeringId): bool
    {
        return $this->offerings()->delete($offeringId);
    }

    // Package convenience methods
    public function getPackage(string $packageId): PackageData
    {
        return $this->packages()->get($packageId);
    }

    /**
     * @param  array<string,mixed>  $extra
     * @return ListPage<PackageData>
     */
    public function getPackageList(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->packages()->list($limit, $startingAfter, $extra);
    }

    /**
     * Create a package.
     */
    public function createPackage(string $lookupKey, string $displayName, ?int $position = null): PackageData
    {
        return $this->packages()->create($lookupKey, $displayName, $position);
    }

    /**
     * Update a package.
     *
     * Note: any null values will be ignored and not updated
     */
    public function updatePackage(string $packageId, ?string $displayName, ?int $position = null): PackageData
    {
        return $this->packages()->update($packageId, $displayName, $position);
    }

    public function deletePackage(string $packageId): bool
    {
        return $this->packages()->delete($packageId);
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<ProductData>
     */
    public function getPackageProducts(string $packageId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->packages()->listOfProducts($packageId, $limit, $startingAfter, $extra);
    }

    // Product convenience methods
    public function getProduct(string $productId): ProductData
    {
        return $this->products()->get($productId);
    }

    /**
     * @param  array<string,mixed>  $extra
     * @return ListPage<ProductData>
     */
    public function getProductList(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->products()->list($limit, $startingAfter, $extra);
    }

    /**
     * Create a product.
     */
    public function createProduct(string $storeIdentifier, string $appId, string $type, ?string $displayName = null): ProductData
    {
        return $this->products()->create($storeIdentifier, $appId, $type, $displayName);
    }

    public function deleteProduct(string $productId): bool
    {
        return $this->products()->delete($productId);
    }

    // Project convenience methods
    /**
     * @param  array<string,mixed>  $extra
     * @return ListPage<ProjectData>
     */
    public function getProjectList(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->projects()->list($limit, $startingAfter, $extra);
    }

    // Purchase convenience methods
    public function getPurchase(string $purchaseId): PurchaseData
    {
        return $this->purchases()->get($purchaseId);
    }

    public function getPurchaseEntitlements(string $purchaseId): Response
    {
        return $this->purchases()->listOfEntitlements($purchaseId);
    }

    // Subscription convenience methods
    public function getSubscription(string $subscriptionId): SubscriptionData
    {
        return $this->subscriptions()->get($subscriptionId);
    }

    public function getSubscriptionEntitlements(string $subscriptionId): Response
    {
        return $this->subscriptions()->listOfEntitlements($subscriptionId);
    }

    public function getSubscriptionTransactions(string $subscriptionId): Response
    {
        return $this->subscriptions()->listOfTransactions($subscriptionId);
    }

    public function getSubscriptionCustomerPortalUrl(string $subscriptionId): Response
    {
        return $this->subscriptions()->getCustomerPortalUrl($subscriptionId);
    }

    public function cancelWebBillingSubscription(string $subscriptionId): Response
    {
        return $this->subscriptions()->cancelWebBillingSubscription($subscriptionId);
    }

    public function refundWebBillingSubscription(string $subscriptionId): Response
    {
        return $this->subscriptions()->refundWebBillingSubscription($subscriptionId);
    }
}
