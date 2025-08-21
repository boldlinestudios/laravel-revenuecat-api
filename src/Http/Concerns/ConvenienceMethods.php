<?php

namespace BoldlineStudios\RevenueCatApi\Http\Concerns;

use Illuminate\Http\Client\Response;

trait ConvenienceMethods
{
    /**
     * @param  array<string,mixed>  $query
     * @return array{0:int,1:?string,2:array<string,mixed>}
     */
    private function normalizeListQuery(array $query): array
    {
        $limit = isset($query['limit']) ? (int) $query['limit'] : 20;
        $startingAfter = $query['starting_after'] ?? null;

        unset($query['limit'], $query['starting_after']);

        return [$limit, is_string($startingAfter) ? $startingAfter : null, $query];
    }

    // App convenience methods
    public function getApp(string $appId): Response
    {
        return $this->apps()->get($appId);
    }

    public function getAppList(array $query = []): Response
    {
        [$limit, $startingAfter, $extra] = $this->normalizeListQuery($query);

        return $this->apps()->list($limit, $startingAfter, $extra);
    }

    public function createApp(array $data): Response
    {
        return $this->apps()->create($data);
    }

    public function updateApp(string $appId, array $data): Response
    {
        return $this->apps()->update($appId, $data);
    }

    public function deleteApp(string $appId): Response
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
    public function getCustomer(string $customerId): Response
    {
        return $this->customers()->get($customerId);
    }

    public function getCustomerList(array $query = []): Response
    {
        [$limit, $startingAfter, $extra] = $this->normalizeListQuery($query);

        return $this->customers()->list($limit, $startingAfter, $extra);
    }

    public function createCustomer(array $data): Response
    {
        return $this->customers()->create($data);
    }

    public function deleteCustomer(string $customerId): Response
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
    public function getEntitlement(string $entitlementId): Response
    {
        return $this->entitlements()->get($entitlementId);
    }

    public function getEntitlementList(array $query = []): Response
    {
        [$limit, $startingAfter, $extra] = $this->normalizeListQuery($query);

        return $this->entitlements()->list($limit, $startingAfter, $extra);
    }

    public function createEntitlement(array $data): Response
    {
        return $this->entitlements()->create($data);
    }

    public function updateEntitlement(string $entitlementId, array $data): Response
    {
        return $this->entitlements()->update($entitlementId, $data);
    }

    public function deleteEntitlement(string $entitlementId): Response
    {
        return $this->entitlements()->delete($entitlementId);
    }

    public function getEntitlementProducts(string $entitlementId): Response
    {
        return $this->entitlements()->listOfProducts($entitlementId);
    }

    // Offering convenience methods
    public function getOffering(string $offeringId): Response
    {
        return $this->offerings()->get($offeringId);
    }

    public function getOfferingList(array $query = []): Response
    {
        [$limit, $startingAfter, $extra] = $this->normalizeListQuery($query);

        return $this->offerings()->list($limit, $startingAfter, $extra);
    }

    public function createOffering(array $data): Response
    {
        return $this->offerings()->create($data);
    }

    public function updateOffering(string $offeringId, array $data): Response
    {
        return $this->offerings()->update($offeringId, $data);
    }

    public function deleteOffering(string $offeringId): Response
    {
        return $this->offerings()->delete($offeringId);
    }

    // Package convenience methods
    public function getPackage(string $packageId): Response
    {
        return $this->packages()->get($packageId);
    }

    public function getPackageList(array $query = []): Response
    {
        [$limit, $startingAfter, $extra] = $this->normalizeListQuery($query);

        return $this->packages()->list($limit, $startingAfter, $extra);
    }

    public function createPackage(array $data): Response
    {
        return $this->packages()->create($data);
    }

    public function updatePackage(string $packageId, array $data): Response
    {
        return $this->packages()->update($packageId, $data);
    }

    public function deletePackage(string $packageId): Response
    {
        return $this->packages()->delete($packageId);
    }

    public function getPackageProducts(string $packageId): Response
    {
        return $this->packages()->listOfProducts($packageId);
    }

    // Product convenience methods
    public function getProduct(string $productId): Response
    {
        return $this->products()->get($productId);
    }

    public function getProductList(array $query = []): Response
    {
        [$limit, $startingAfter, $extra] = $this->normalizeListQuery($query);

        return $this->products()->list($limit, $startingAfter, $extra);
    }

    public function createProduct(array $data): Response
    {
        return $this->products()->create($data);
    }

    public function deleteProduct(string $productId): Response
    {
        return $this->products()->delete($productId);
    }

    // Project convenience methods
    public function getProjectList(array $query = []): Response
    {
        [$limit, $startingAfter, $extra] = $this->normalizeListQuery($query);

        return $this->projects()->list($limit, $startingAfter, $extra);
    }

    // Purchase convenience methods
    public function getPurchase(string $purchaseId): Response
    {
        return $this->purchases()->get($purchaseId);
    }

    public function getPurchaseEntitlements(string $purchaseId): Response
    {
        return $this->purchases()->listOfEntitlements($purchaseId);
    }

    // Subscription convenience methods
    public function getSubscription(string $subscriptionId): Response
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
