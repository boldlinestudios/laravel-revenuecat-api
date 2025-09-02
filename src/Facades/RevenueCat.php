<?php

namespace BoldlineStudios\RevenueCatApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Endpoint accessors
 *
 * @method static \BoldlineStudios\RevenueCatApi\Endpoints\App apps()
 * @method static \BoldlineStudios\RevenueCatApi\Endpoints\Customer customers()
 * @method static \BoldlineStudios\RevenueCatApi\Endpoints\Entitlement entitlements()
 * @method static \BoldlineStudios\RevenueCatApi\Endpoints\Offering offerings()
 * @method static \BoldlineStudios\RevenueCatApi\Endpoints\Package packages()
 * @method static \BoldlineStudios\RevenueCatApi\Endpoints\Product products()
 * @method static \BoldlineStudios\RevenueCatApi\Endpoints\Project projects()
 * @method static \BoldlineStudios\RevenueCatApi\Endpoints\Purchase purchases()
 * @method static \BoldlineStudios\RevenueCatApi\Endpoints\Subscription subscriptions()
 *
 * Convenience methods (DTOs for resources; Response for utility endpoints)
 *
 * Apps
 * @method static \BoldlineStudios\RevenueCatApi\Data\AppData getApp(string $appId)
 * @method static \BoldlineStudios\RevenueCatApi\Data\ListPage<\BoldlineStudios\RevenueCatApi\Data\AppData> getAppList(int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldlineStudios\RevenueCatApi\Data\AppData createApp(string $name, string $type, array<string, array<string, mixed>> $storeConfig)
 * @method static \BoldlineStudios\RevenueCatApi\Data\AppData updateApp(string $appId, ?string $name, array<string, array<string, mixed>> $storeConfig)
 * @method static bool deleteApp(string $appId)
 * @method static \Illuminate\Http\Client\Response getAppStoreKitConfig(string $appId)
 * @method static \BoldlineStudios\RevenueCatApi\Data\ListPage<\BoldlineStudios\RevenueCatApi\Data\App\PublicApiKeyData> getAppPublicKeys(string $appId)
 *
 * Customers
 * @method static \BoldlineStudios\RevenueCatApi\Data\CustomerData getCustomer(string $customerId)
 * @method static \BoldlineStudios\RevenueCatApi\Data\ListPage<\BoldlineStudios\RevenueCatApi\Data\CustomerData> getCustomerList(int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldlineStudios\RevenueCatApi\Data\CustomerData createCustomer(string $id, list<array{name: string, value: string}> $attributes)
 * @method static bool deleteCustomer(string $customerId)
 * @method static \BoldlineStudios\RevenueCatApi\Data\ListPage<\BoldlineStudios\RevenueCatApi\Data\SubscriptionData> getCustomerSubscriptions(string $customerId, int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldlineStudios\RevenueCatApi\Data\ListPage<\BoldlineStudios\RevenueCatApi\Data\PurchaseData> getCustomerPurchases(string $customerId, int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldlineStudios\RevenueCatApi\Data\ListPage<\BoldlineStudios\RevenueCatApi\Data\Customer\ActiveEntitlementData> getCustomerActiveEntitlements(string $customerId)
 * @method static \BoldlineStudios\RevenueCatApi\Data\ListPage<\BoldlineStudios\RevenueCatApi\Data\Customer\AliasData> getCustomerAliases(string $customerId, int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldlineStudios\RevenueCatApi\Data\ListPage<\BoldlineStudios\RevenueCatApi\Data\Customer\VirtualCurrencyBalanceData> getCustomerVirtualCurrencyBalances(string $customerId, int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldlineStudios\RevenueCatApi\Data\ListPage<\BoldlineStudios\RevenueCatApi\Data\Customer\AttributeData> getCustomerAttributes(string $customerId, int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 *
 * Entitlements
 * @method static \BoldlineStudios\RevenueCatApi\Data\EntitlementData getEntitlement(string $entitlementId)
 * @method static \BoldlineStudios\RevenueCatApi\Data\ListPage<\BoldlineStudios\RevenueCatApi\Data\EntitlementData> getEntitlementList(int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldlineStudios\RevenueCatApi\Data\EntitlementData createEntitlement(string $lookupKey, string $displayName)
 * @method static \BoldlineStudios\RevenueCatApi\Data\EntitlementData updateEntitlement(string $entitlementId, string $displayName)
 * @method static bool deleteEntitlement(string $entitlementId)
 * @method static \Illuminate\Http\Client\Response getEntitlementProducts(string $entitlementId)
 *
 * Offerings
 * @method static \BoldlineStudios\RevenueCatApi\Data\OfferingData getOffering(string $offeringId)
 * @method static \BoldlineStudios\RevenueCatApi\Data\ListPage<\BoldlineStudios\RevenueCatApi\Data\OfferingData> getOfferingList(int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldlineStudios\RevenueCatApi\Data\OfferingData createOffering(string $lookupKey, string $displayName, ?array<string, mixed> $metadata = null)
 * @method static \BoldlineStudios\RevenueCatApi\Data\OfferingData updateOffering(string $offeringId, ?string $displayName, ?bool $isCurrent, ?array<string, mixed> $metadata = null)
 * @method static bool deleteOffering(string $offeringId)
 *
 * Packages
 * @method static \BoldlineStudios\RevenueCatApi\Data\PackageData getPackage(string $packageId)
 * @method static \BoldlineStudios\RevenueCatApi\Data\ListPage<\BoldlineStudios\RevenueCatApi\Data\PackageData> getPackageList(int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldlineStudios\RevenueCatApi\Data\PackageData createPackage(string $lookupKey, string $displayName, ?int $position = null)
 * @method static \BoldlineStudios\RevenueCatApi\Data\PackageData updatePackage(string $packageId, ?string $displayName, ?int $position = null)
 * @method static bool deletePackage(string $packageId)
 * @method static \BoldlineStudios\RevenueCatApi\Data\ListPage<\BoldlineStudios\RevenueCatApi\Data\ProductData> getPackageProducts(string $packageId, int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = [])
 * @method static \BoldlineStudios\RevenueCatApi\Data\PackageData attachPackageProducts(string $packageId, list<array{product_id: string, eligibility_criteria: string}> $productAssociationList)
 * @method static \BoldlineStudios\RevenueCatApi\Data\PackageData detachPackageProducts(string $packageId, array<string> $productIds)
 *
 * Products
 * @method static \BoldlineStudios\RevenueCatApi\Data\ProductData getProduct(string $productId)
 * @method static \BoldlineStudios\RevenueCatApi\Data\ListPage<\BoldlineStudios\RevenueCatApi\Data\ProductData> getProductList(int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldlineStudios\RevenueCatApi\Data\ProductData createProduct(string $storeIdentifier, string $appId, string $type, ?string $displayName = null)
 * @method static bool deleteProduct(string $productId)
 *
 * Projects
 * @method static \BoldlineStudios\RevenueCatApi\Data\ListPage<\BoldlineStudios\RevenueCatApi\Data\ProjectData> getProjectList(int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 *
 * Purchases
 * @method static \BoldlineStudios\RevenueCatApi\Data\PurchaseData getPurchase(string $purchaseId)
 * @method static \BoldlineStudios\RevenueCatApi\Data\ListPage<\BoldlineStudios\RevenueCatApi\Data\EntitlementData> getPurchaseEntitlements(string $purchaseId, int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = [])
 * @method static \BoldlineStudios\RevenueCatApi\Data\PurchaseData refundWebBillingPurchase(string $purchaseId)
 * @method static \BoldlineStudios\RevenueCatApi\Data\ListPage<\BoldlineStudios\RevenueCatApi\Data\PurchaseData> searchPurchasesByIdentifier(string $storePurchaseIdentifier)
 * @method static \BoldlineStudios\RevenueCatApi\Data\PaywallData createPaywall(string $offeringId)
 *
 * Subscriptions
 * @method static \BoldlineStudios\RevenueCatApi\Data\SubscriptionData getSubscription(string $subscriptionId)
 * @method static \Illuminate\Http\Client\Response getSubscriptionEntitlements(string $subscriptionId)
 * @method static \Illuminate\Http\Client\Response getSubscriptionTransactions(string $subscriptionId)
 * @method static \Illuminate\Http\Client\Response getSubscriptionCustomerPortalUrl(string $subscriptionId)
 * @method static \Illuminate\Http\Client\Response cancelWebBillingSubscription(string $subscriptionId)
 * @method static \Illuminate\Http\Client\Response refundWebBillingSubscription(string $subscriptionId)
 */
class RevenueCat extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \BoldlineStudios\RevenueCatApi\Http\RevenueCatClient::class;
    }
}
