<?php

namespace BoldlineStudios\RevenueCatApi\Http\Concerns;

use BoldlineStudios\RevenueCatApi\Data\AppData;
use BoldlineStudios\RevenueCatApi\Data\Customer\ActiveEntitlementData;
use BoldlineStudios\RevenueCatApi\Data\Customer\AliasData;
use BoldlineStudios\RevenueCatApi\Data\Customer\AttributeData;
use BoldlineStudios\RevenueCatApi\Data\Customer\VirtualCurrencyBalanceData;
use BoldlineStudios\RevenueCatApi\Data\CustomerData;
use BoldlineStudios\RevenueCatApi\Data\EntitlementData;
use BoldlineStudios\RevenueCatApi\Data\InvoiceData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\OfferingData;
use BoldlineStudios\RevenueCatApi\Data\PackageData;
use BoldlineStudios\RevenueCatApi\Data\PaywallData;
use BoldlineStudios\RevenueCatApi\Data\ProductData;
use BoldlineStudios\RevenueCatApi\Data\ProjectData;
use BoldlineStudios\RevenueCatApi\Data\PurchaseData;
use BoldlineStudios\RevenueCatApi\Data\SubscriptionData;
use BoldlineStudios\RevenueCatApi\Data\Subscriptions\TransactionData;
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
    public function listApps(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->apps()->all($limit, $startingAfter, $extra);
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
     * @param  string  $appId  The app ID to update
     * @param  string|null  $name  The new name for the app (optional)
     * @param  array<string, array<string, mixed>>  $storeConfig  Store configuration
     *
     * Example payload for $storeConfig:
     * [
     *   'play_store' => [
     *     'bundle_id' => 'com.example.app',
     *     'shared_secret' => '1234567890abcdef1234567890abcdef',
     *   ],
     * ]
     */
    public function updateApp(string $appId, ?string $name = null, ?array $storeConfig = []): AppData
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

    /**
     * @return ListPage<\BoldlineStudios\RevenueCatApi\Data\App\PublicApiKeyData>
     */
    public function listAppPublicKeys(string $appId)
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
    public function listCustomers(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->customers()->all($limit, $startingAfter, $extra);
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
    public function listCustomerSubscriptions(
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
    public function listCustomerPurchases(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->customers()->listOfPurchases($customerId, $limit, $startingAfter, $extra);
    }

    /**
     * @return ListPage<ActiveEntitlementData>
     */
    public function listCustomerActiveEntitlements(string $customerId): ListPage
    {
        return $this->customers()->listOfActiveEntitlements($customerId);
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<AliasData>
     */
    public function listCustomerAliases(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->customers()->listOfAliases($customerId, $limit, $startingAfter, $extra);
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<VirtualCurrencyBalanceData>
     */
    public function listCustomerVirtualCurrencyBalances(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->customers()->listOfVirtualCurrencyBalances($customerId, $limit, $startingAfter, $extra);
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<AttributeData>
     */
    public function listCustomerAttributes(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->customers()->listOfAttributes($customerId, $limit, $startingAfter, $extra);
    }

    /**
     * Set attributes for a customer.
     *
     * @param  list<array{name: string, value: string}>  $attributes
     * @return ListPage<AttributeData>
     */
    public function setCustomerAttributes(string $customerId, array $attributes): ListPage
    {
        return $this->customers()->setAttributes($customerId, $attributes);
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
    public function listEntitlements(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->entitlements()->all($limit, $startingAfter, $extra);
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
     * Attach a set of products to an entitlement
     *
     * @param  array<string>  $productIds
     */
    public function attachEntitlementProducts(string $entitlementId, array $productIds): EntitlementData
    {
        return $this->entitlements()->attachProducts($entitlementId, $productIds);
    }

    /**
     * Detach a set of products from an entitlement
     *
     * @param  array<string>  $productIds
     */
    public function detachEntitlementProducts(string $entitlementId, array $productIds): EntitlementData
    {
        return $this->entitlements()->detachProducts($entitlementId, $productIds);
    }

    /**
     * @return ListPage<ProductData>
     */
    public function listEntitlementProducts(string $entitlementId): ListPage
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
    public function listOfferings(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->offerings()->all($limit, $startingAfter, $extra);
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
    public function listPackages(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->packages()->all($limit, $startingAfter, $extra);
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
    public function listPackageProducts(string $packageId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->packages()->listOfProducts($packageId, $limit, $startingAfter, $extra);
    }

    /**
     * Attach a set of products to a package.
     *
     * @param  list<array{product_id: string, eligibility_criteria: string}>  $productAssociationList
     */
    public function attachPackageProducts(string $packageId, array $productAssociationList): PackageData
    {
        return $this->packages()->attachProducts($packageId, $productAssociationList);
    }

    /**
     * Detach a set of products from a package.
     *
     * @param  array<string>  $productIds
     */
    public function detachPackageProducts(string $packageId, array $productIds): PackageData
    {
        return $this->packages()->detachProducts($packageId, $productIds);
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
    public function listProducts(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->products()->all($limit, $startingAfter, $extra);
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
    public function listProjects(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->projects()->all($limit, $startingAfter, $extra);
    }

    // Purchase convenience methods
    public function getPurchase(string $purchaseId): PurchaseData
    {
        return $this->purchases()->get($purchaseId);
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<EntitlementData>
     */
    public function listPurchaseEntitlements(string $purchaseId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->purchases()->listOfEntitlements($purchaseId, $limit, $startingAfter, $extra);
    }

    public function refundWebBillingPurchase(string $purchaseId): PurchaseData
    {
        return $this->purchases()->refundWebBillingPurchase($purchaseId);
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
        return $this->purchases()->searchPurchasesByIdentifier($storePurchaseIdentifier);
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<InvoiceData>
     */
    public function listCustomerInvoices(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->invoices()->listCustomerInvoices($customerId, $limit, $startingAfter, $extra);
    }

    public function createPaywall(string $offeringId): PaywallData
    {
        return $this->paywalls()->create($offeringId);
    }

    // Subscription convenience methods
    public function getSubscription(string $subscriptionId): SubscriptionData
    {
        return $this->subscriptions()->get($subscriptionId);
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<EntitlementData>
     */
    public function listSubscriptionEntitlements(string $subscriptionId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->subscriptions()->listOfEntitlements($subscriptionId, $limit, $startingAfter, $extra);
    }

    public function getSubscriptionCustomerPortalUrl(string $subscriptionId): Response
    {
        return $this->subscriptions()->getCustomerPortalUrl($subscriptionId);
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<TransactionData>
     */
    public function listSubscriptionTransactions(string $subscriptionId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->subscriptions()->listOfTransactions($subscriptionId, $limit, $startingAfter, $extra);
    }

    public function cancelWebBillingSubscription(string $subscriptionId): SubscriptionData
    {
        return $this->subscriptions()->cancelWebBillingSubscription($subscriptionId);
    }

    public function refundWebBillingSubscription(string $subscriptionId): SubscriptionData
    {
        return $this->subscriptions()->refundWebBillingSubscription($subscriptionId);
    }

    /**
     * Refund a Play Store subscription's transaction.
     * This endpoint does not cancel the subscription or revoke access to it.
     */
    public function refundPlayStoreSubscriptionTransaction(string $subscriptionId, string $transactionId): TransactionData
    {
        return $this->subscriptions()->refundPlayStoreSubscriptionTransaction($subscriptionId, $transactionId);
    }
}
