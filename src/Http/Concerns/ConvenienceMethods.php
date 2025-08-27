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
     * Example payload:
     * [
     *   'name' => 'My App',
     *   'type' => 'app_store',
     *   'app_store' => ['bundle_id' => 'com.example.app', ...],
     * ]
     *
     * @param  array{name: string, type: string} & array<string, mixed>  $data
     */
    public function createApp(array $data): AppData
    {
        return $this->apps()->create($data);
    }

    /**
     * Update an app.
     *
     * @param  array{name: string} & array<string, mixed>  $data
     *
     * Example payload:
     * [
     *   'name' => 'My App',
     *   'app_store' => ['bundle_id' => 'com.example.app', ...],
     * ]
     */
    public function updateApp(string $appId, array $data): AppData
    {
        return $this->apps()->update($appId, $data);
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

    public function getCustomerSubscriptions(string $customerId): Response
    {
        return $this->customers()->listOfSubscriptions($customerId);
    }

    public function getCustomerPurchases(string $customerId): Response
    {
        return $this->customers()->listOfPurchases($customerId);
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

    /**
     * Update an entitlement.
     *
     * @param array{
     *   display_name: string,
     * } $data Entitlement payload with display name.
     */
    public function updateEntitlement(string $entitlementId, array $data): EntitlementData
    {
        return $this->entitlements()->update($entitlementId, $data);
    }

    public function deleteEntitlement(string $entitlementId): bool
    {
        return $this->entitlements()->delete($entitlementId);
    }

    public function getEntitlementProducts(string $entitlementId): Response
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
     * @param array{
     *   lookup_key: string,
     *   display_name: string,
     *   metadata?: array<string, mixed>
     * } $data
     */
    public function createOffering(array $data): OfferingData
    {
        return $this->offerings()->create($data);
    }

    /**
     * Update an offering.
     *
     * @param array{
     *   lookup_key: string,
     *   display_name: string,
     *   metadata?: array<string, mixed>
     * } $data Offering payload with lookup key, display name, and metadata.
     */
    public function updateOffering(string $offeringId, array $data): OfferingData
    {
        return $this->offerings()->update($offeringId, $data);
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
     *
     * @param array{
     *   lookup_key: string,
     *   display_name: string,
     *   position: int,
     * } $data Package payload with lookup key, display name, and position.
     */
    public function createPackage(array $data): PackageData
    {
        return $this->packages()->create($data);
    }

    /**
     * Update a package.
     *
     * @param array{
     *   display_name: string,
     *   position: int,
     * } $data Package payload with display name and position.
     */
    public function updatePackage(string $packageId, array $data): PackageData
    {
        return $this->packages()->update($packageId, $data);
    }

    public function deletePackage(string $packageId): bool
    {
        return $this->packages()->delete($packageId);
    }

    public function getPackageProducts(string $packageId): Response
    {
        return $this->packages()->listOfProducts($packageId);
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
     * @param array{
     *   store_identifier: string,
     *   app_id: string,
     *   type: 'subscription'|'one_time'|'consumable'|'non_consumable'|'non_renewing_subscription',
     *   display_name: string|null
     * } $data
     */
    public function createProduct(array $data): ProductData
    {
        return $this->products()->create($data);
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
