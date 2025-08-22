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
 * Convenience methods (return Illuminate\Http\Client\Response)
 *
 * Apps
 * @method static \Illuminate\Http\Client\Response getApp(string $appId)
 * @method static \Illuminate\Http\Client\Response getAppList(array<string,mixed> $query = [])
 * @method static \Illuminate\Http\Client\Response createApp(array<string,mixed> $data)
 * @method static \Illuminate\Http\Client\Response updateApp(string $appId, array<string,mixed> $data)
 * @method static \Illuminate\Http\Client\Response deleteApp(string $appId)
 * @method static \Illuminate\Http\Client\Response getAppStoreKitConfig(string $appId)
 * @method static \Illuminate\Http\Client\Response getAppPublicKeys(string $appId)
 *
 * Customers
 * @method static \Illuminate\Http\Client\Response getCustomer(string $customerId)
 * @method static \Illuminate\Http\Client\Response getCustomerList(array<string,mixed> $query = [])
 * @method static \Illuminate\Http\Client\Response createCustomer(array<string,mixed> $data)
 * @method static \Illuminate\Http\Client\Response deleteCustomer(string $customerId)
 * @method static \Illuminate\Http\Client\Response getCustomerSubscriptions(string $customerId)
 * @method static \Illuminate\Http\Client\Response getCustomerPurchases(string $customerId)
 * @method static \Illuminate\Http\Client\Response getCustomerActiveEntitlements(string $customerId)
 * @method static \Illuminate\Http\Client\Response getCustomerAliases(string $customerId)
 * @method static \Illuminate\Http\Client\Response getCustomerVirtualCurrencyBalances(string $customerId)
 * @method static \Illuminate\Http\Client\Response getCustomerAttributes(string $customerId)
 *
 * Entitlements
 * @method static \Illuminate\Http\Client\Response getEntitlement(string $entitlementId)
 * @method static \Illuminate\Http\Client\Response getEntitlementList(array<string,mixed> $query = [])
 * @method static \Illuminate\Http\Client\Response createEntitlement(array<string,mixed> $data)
 * @method static \Illuminate\Http\Client\Response updateEntitlement(string $entitlementId, array<string,mixed> $data)
 * @method static \Illuminate\Http\Client\Response deleteEntitlement(string $entitlementId)
 * @method static \Illuminate\Http\Client\Response getEntitlementProducts(string $entitlementId)
 *
 * Offerings
 * @method static \Illuminate\Http\Client\Response getOffering(string $offeringId)
 * @method static \Illuminate\Http\Client\Response getOfferingList(array<string,mixed> $query = [])
 * @method static \Illuminate\Http\Client\Response createOffering(array<string,mixed> $data)
 * @method static \Illuminate\Http\Client\Response updateOffering(string $offeringId, array<string,mixed> $data)
 * @method static \Illuminate\Http\Client\Response deleteOffering(string $offeringId)
 *
 * Packages
 * @method static \Illuminate\Http\Client\Response getPackage(string $packageId)
 * @method static \Illuminate\Http\Client\Response getPackageList(array<string,mixed> $query = [])
 * @method static \Illuminate\Http\Client\Response createPackage(array<string,mixed> $data)
 * @method static \Illuminate\Http\Client\Response updatePackage(string $packageId, array<string,mixed> $data)
 * @method static \Illuminate\Http\Client\Response deletePackage(string $packageId)
 * @method static \Illuminate\Http\Client\Response getPackageProducts(string $packageId)
 *
 * Products
 * @method static \Illuminate\Http\Client\Response getProduct(string $productId)
 * @method static \Illuminate\Http\Client\Response getProductList(array<string,mixed> $query = [])
 * @method static \Illuminate\Http\Client\Response createProduct(array<string,mixed> $data)
 * @method static \Illuminate\Http\Client\Response deleteProduct(string $productId)
 *
 * Projects
 * @method static \Illuminate\Http\Client\Response getProjectList(array<string,mixed> $query = [])
 *
 * Purchases
 * @method static \Illuminate\Http\Client\Response getPurchase(string $purchaseId)
 * @method static \Illuminate\Http\Client\Response getPurchaseEntitlements(string $purchaseId)
 *
 * Subscriptions
 * @method static \Illuminate\Http\Client\Response getSubscription(string $subscriptionId)
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
