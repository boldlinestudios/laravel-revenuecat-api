<?php

namespace BoldLineStudios\RevenueCatApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Endpoint accessors
 *
 * @method static \BoldLineStudios\RevenueCatApi\Endpoints\App apps()
 * @method static \BoldLineStudios\RevenueCatApi\Endpoints\Customer customers()
 * @method static \BoldLineStudios\RevenueCatApi\Endpoints\Entitlement entitlements()
 * @method static \BoldLineStudios\RevenueCatApi\Endpoints\Offering offerings()
 * @method static \BoldLineStudios\RevenueCatApi\Endpoints\Package packages()
 * @method static \BoldLineStudios\RevenueCatApi\Endpoints\Product products()
 * @method static \BoldLineStudios\RevenueCatApi\Endpoints\Project projects()
 * @method static \BoldLineStudios\RevenueCatApi\Endpoints\Purchase purchases()
 * @method static \BoldLineStudios\RevenueCatApi\Endpoints\Subscription subscriptions()
 * @method static \BoldLineStudios\RevenueCatApi\Endpoints\Paywall paywalls()
 * @method static \BoldLineStudios\RevenueCatApi\Endpoints\Invoice invoices()
 *
 * Convenience methods (DTOs for resources; Response for utility endpoints)
 *
 * Apps
 * @method static \BoldLineStudios\RevenueCatApi\Data\AppData getApp(string $appId)
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\AppData> listApps(int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldLineStudios\RevenueCatApi\Data\AppData createApp(string $name, string $type, array<string, array<string, mixed>> $storeConfig)
 * @method static \BoldLineStudios\RevenueCatApi\Data\AppData updateApp(string $appId, ?string $name = null, ?array<string, array<string, mixed>> $storeConfig = null) Update an app
 * @method static bool deleteApp(string $appId)
 * @method static \BoldLineStudios\RevenueCatApi\Data\App\StoreKitConfigData getAppStoreKitConfig(string $appId)
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\App\PublicApiKeyData> listAppPublicKeys(string $appId)
 *
 * Customers
 * @method static \BoldLineStudios\RevenueCatApi\Data\CustomerData getCustomer(string $customerId)
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\CustomerData> listCustomers(int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldLineStudios\RevenueCatApi\Data\CustomerData createCustomer(string $id, list<array{name: string, value: string}> $attributes)
 * @method static bool deleteCustomer(string $customerId)
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\SubscriptionData> listCustomerSubscriptions(string $customerId, int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\PurchaseData> listCustomerPurchases(string $customerId, int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\Customer\ActiveEntitlementData> listCustomerActiveEntitlements(string $customerId)
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\Customer\AliasData> listCustomerAliases(string $customerId, int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\Customer\VirtualCurrencyBalanceData> listCustomerVirtualCurrencyBalances(string $customerId, int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\Customer\AttributeData> listCustomerAttributes(string $customerId, int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\Customer\AttributeData> setCustomerAttributes(string $customerId, list<array{name: string, value: string}> $attributes)
 *
 * Entitlements
 * @method static \BoldLineStudios\RevenueCatApi\Data\EntitlementData getEntitlement(string $entitlementId)
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\EntitlementData> listEntitlements(int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldLineStudios\RevenueCatApi\Data\EntitlementData createEntitlement(string $lookupKey, string $displayName)
 * @method static \BoldLineStudios\RevenueCatApi\Data\EntitlementData updateEntitlement(string $entitlementId, string $displayName)
 * @method static bool deleteEntitlement(string $entitlementId)
 * @method static \BoldLineStudios\RevenueCatApi\Data\EntitlementData attachEntitlementProducts(string $entitlementId, array<string> $productIds)
 * @method static \BoldLineStudios\RevenueCatApi\Data\EntitlementData detachEntitlementProducts(string $entitlementId, array<string> $productIds)
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\ProductData> listEntitlementProducts(string $entitlementId)
 *
 * Invoices
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\InvoiceData> listCustomerInvoices(string $customerId, int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = [])
 *
 * Offerings
 * @method static \BoldLineStudios\RevenueCatApi\Data\OfferingData getOffering(string $offeringId)
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\OfferingData> listOfferings(int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldLineStudios\RevenueCatApi\Data\OfferingData createOffering(string $lookupKey, string $displayName, ?array<string, mixed> $metadata = null)
 * @method static \BoldLineStudios\RevenueCatApi\Data\OfferingData updateOffering(string $offeringId, ?string $displayName = null, ?bool $isCurrent = null, ?array<string, mixed> $metadata = null)
 * @method static bool deleteOffering(string $offeringId)
 *
 * Packages
 * @method static \BoldLineStudios\RevenueCatApi\Data\PackageData getPackage(string $packageId)
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\PackageData> listofPackagesinOffering(string $offeringId, int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldLineStudios\RevenueCatApi\Data\PackageData createPackage(string $offeringId, string $lookupKey, string $displayName, ?int $position = null)
 * @method static \BoldLineStudios\RevenueCatApi\Data\PackageData updatePackage(string $packageId, ?string $displayName, ?int $position = null)
 * @method static bool deletePackage(string $packageId)
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\ProductData> listPackageProducts(string $packageId, int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = [])
 * @method static \BoldLineStudios\RevenueCatApi\Data\PackageData attachPackageProducts(string $packageId, list<array{product_id: string, eligibility_criteria: string}> $productAssociationList)
 * @method static \BoldLineStudios\RevenueCatApi\Data\PackageData detachPackageProducts(string $packageId, array<string> $productIds)
 *
 * Products
 * @method static \BoldLineStudios\RevenueCatApi\Data\ProductData getProduct(string $productId)
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\ProductData> listProducts(int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 * @method static \BoldLineStudios\RevenueCatApi\Data\ProductData createProduct(string $storeIdentifier, string $appId, string $type, ?string $displayName = null)
 * @method static bool deleteProduct(string $productId)
 *
 * Projects
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\ProjectData> listProjects(int $limit = 20, ?string $startingAfter = null, array<string,mixed> $extra = [])
 *
 * Purchases
 * @method static \BoldLineStudios\RevenueCatApi\Data\PurchaseData getPurchase(string $purchaseId)
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\EntitlementData> listPurchaseEntitlements(string $purchaseId, int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = [])
 * @method static \BoldLineStudios\RevenueCatApi\Data\PurchaseData refundWebBillingPurchase(string $purchaseId)
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\PurchaseData> searchPurchasesByIdentifier(string $storePurchaseIdentifier)
 * @method static \BoldLineStudios\RevenueCatApi\Data\PaywallData createPaywall(string $offeringId)
 *
 * Subscriptions
 * @method static \BoldLineStudios\RevenueCatApi\Data\SubscriptionData getSubscription(string $subscriptionId)
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\EntitlementData> listSubscriptionEntitlements(string $subscriptionId, int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = [])
 * @method static \BoldLineStudios\RevenueCatApi\Data\ListPage<\BoldLineStudios\RevenueCatApi\Data\Subscriptions\TransactionData> listSubscriptionTransactions(string $subscriptionId, int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = [])
 * @method static \BoldLineStudios\RevenueCatApi\Data\Subscriptions\ManagementUrlData getSubscriptionCustomerPortalUrl(string $subscriptionId)
 * @method static \BoldLineStudios\RevenueCatApi\Data\SubscriptionData cancelWebBillingSubscription(string $subscriptionId)
 * @method static \BoldLineStudios\RevenueCatApi\Data\SubscriptionData refundWebBillingSubscription(string $subscriptionId)
 * @method static \BoldLineStudios\RevenueCatApi\Data\Subscriptions\TransactionData refundPlayStoreSubscriptionTransaction(string $subscriptionId, string $transactionId)
 */
class RevenueCat extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \BoldLineStudios\RevenueCatApi\Http\RevenueCatClient::class;
    }
}
