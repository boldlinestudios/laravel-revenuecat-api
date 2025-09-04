# Endpoint and Convenience Methods

## Quick Navigation

<details>
<summary><strong>Endpoints</strong></summary>

- [Apps](#app-endpoints)
- [Customers](#customer-endpoints)
- [Entitlements](#entitlement-endpoints)
- [Offerings](#offering-endpoints)
- [Packages](#package-endpoints)
- [Products](#product-endpoints)
- [Projects](#project-endpoints)
- [Purchases](#purchase-endpoints)
- [Subscriptions](#subscription-endpoints)
- [Invoices](#invoice-endpoints)
- [Paywalls](#paywall-endpoints)

</details>

<details>
<summary><strong>Convenience Methods</strong></summary>

- [Apps](#app-methods)
- [Customers](#customer-methods)
- [Entitlements](#entitlement-methods)
- [Offerings](#offering-methods)
- [Packages](#package-methods)
- [Products](#product-methods)
- [Projects](#project-methods)
- [Purchases](#purchase-methods)
- [Subscriptions](#subscription-methods)
- [Invoices](#invoice-methods)
- [Paywalls](#paywall-methods)

</details>

---

## **Endpoints Reference**

All endpoint methods below are available through their respective endpoint classes:


## Example Usage
```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$app = RevenueCat::apps()->get('app1a2b3c4');
```

<a id="app-endpoints"></a>
### **App Endpoints** (`RevenueCat::apps()`)
```php
// CRUD operations
get(string $appId): AppData
all(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<AppData>
create(string $name, string $type, array $storeConfig): AppData
update(string $appId, ?string $name, array $storeConfig): AppData
delete(string $appId): bool

// App-specific endpoints
listOfPublicKeys(string $appId): ListPage<PublicApiKeyData>
storeKitConfig(string $appId): Response
```

<a id="customer-endpoints"></a>
### **Customer Endpoints** (`RevenueCat::customers()`)
```php
// CRUD operations
get(string $customerId): CustomerData
all(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<CustomerData>
create(string $id, array $attributes): CustomerData
delete(string $customerId): bool

// Customer relationships
listOfSubscriptions(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<SubscriptionData>
listOfPurchases(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<PurchaseData>
listOfActiveEntitlements(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<ActiveEntitlementData>
listOfAliases(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<AliasData>
listOfVirtualCurrencyBalances(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<VirtualCurrencyBalanceData>
listOfAttributes(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<AttributeData>

// Customer actions
setAttributes(string $customerId, array $attributes): ListPage<AttributeData>
```

<a id="entitlement-endpoints"></a>
### **Entitlement Endpoints** (`RevenueCat::entitlements()`)
```php
get(string $entitlementId): EntitlementData
all(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<EntitlementData>
create(string $lookupKey, string $displayName): EntitlementData
update(string $entitlementId, string $displayName): EntitlementData
delete(string $entitlementId): bool

// Product management
attachProducts(string $entitlementId, array $productIds): EntitlementData
detachProducts(string $entitlementId, array $productIds): EntitlementData
listOfProducts(string $entitlementId): ListPage<ProductData>
```

<a id="offering-endpoints"></a>
### **Offering Endpoints** (`RevenueCat::offerings()`)
```php
get(string $offeringId): OfferingData
all(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<OfferingData>
create(string $lookupKey, string $displayName, ?array $metadata): OfferingData
update(string $offeringId, ?string $displayName, ?bool $isCurrent, ?array $metadata): OfferingData
delete(string $offeringId): bool
```

<a id="package-endpoints"></a>
### **Package Endpoints** (`RevenueCat::packages()`)
```php
get(string $packageId): PackageData
all(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<PackageData>
create(string $lookupKey, string $displayName, ?int $position): PackageData
update(string $packageId, ?string $displayName, ?int $position): PackageData
delete(string $packageId): bool

// Product management
listOfProducts(string $packageId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<ProductData>
attachProducts(string $packageId, array $productAssociationList): PackageData
detachProducts(string $packageId, array $productIds): PackageData
```

<a id="product-endpoints"></a>
### **Product Endpoints** (`RevenueCat::products()`)
```php
get(string $productId): ProductData
all(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<ProductData>
create(string $storeIdentifier, string $appId, string $type, ?string $displayName): ProductData
delete(string $productId): bool
```

<a id="project-endpoints"></a>
### **Project Endpoints** (`RevenueCat::projects()`)
```php
all(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<ProjectData>
```

<a id="purchase-endpoints"></a>
### **Purchase Endpoints** (`RevenueCat::purchases()`)
```php
get(string $purchaseId): PurchaseData
listOfEntitlements(string $purchaseId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<EntitlementData>
refundWebBillingPurchase(string $purchaseId): PurchaseData
searchPurchasesByIdentifier(string $storePurchaseIdentifier): ListPage<PurchaseData>
```

<a id="subscription-endpoints"></a>
### **Subscription Endpoints** (`RevenueCat::subscriptions()`)
```php
get(string $subscriptionId): SubscriptionData
listOfEntitlements(string $subscriptionId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<EntitlementData>
listOfTransactions(string $subscriptionId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<TransactionData>

// Portal & management
getCustomerPortalUrl(string $subscriptionId): Response

// Cancellation & refunds
cancelWebBillingSubscription(string $subscriptionId): SubscriptionData
refundWebBillingSubscription(string $subscriptionId): SubscriptionData
refundPlayStoreSubscriptionTransaction(string $subscriptionId, string $transactionId): TransactionData
```

<a id="invoice-endpoints"></a>
### **Invoice Endpoints** (`RevenueCat::invoices()`)
```php
listCustomerInvoices(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<InvoiceData>
```

<a id="paywall-endpoints"></a>
### **Paywall Endpoints** (`RevenueCat::paywalls()`)
```php
create(string $offeringId): PaywallData
```

---

## **Complete Convenience Methods Reference**

All methods below are available through the `ConvenienceMethods` trait and can be called directly on the `RevenueCat` facade:

## Example Usage
```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$app = RevenueCat::getApp('app1a2b3c4');
```

<a id="app-methods"></a>
### **App Methods**
```php
// Core app operations
getApp(string $appId): AppData
listApps(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<AppData>
createApp(string $name, string $type, array $storeConfig): AppData
updateApp(string $appId, ?string $name, array $storeConfig): AppData
deleteApp(string $appId): bool

// App-specific operations
listAppPublicKeys(string $appId): ListPage<PublicApiKeyData>
getAppStoreKitConfig(string $appId): Response
```

<a id="customer-methods"></a>
### **Customer Methods**
```php
// CRUD operations
getCustomer(string $customerId): CustomerData
listCustomers(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<CustomerData>
createCustomer(string $id, array $attributes): CustomerData
deleteCustomer(string $customerId): bool

// Customer relationships
listCustomerSubscriptions(string $customerId): ListPage<SubscriptionData>
listCustomerPurchases(string $customerId): ListPage<PurchaseData>
listCustomerActiveEntitlements(string $customerId): ListPage<ActiveEntitlementData>
listCustomerAliases(string $customerId): ListPage<AliasData>
listCustomerVirtualCurrencyBalances(string $customerId): ListPage<VirtualCurrencyBalanceData>
listCustomerAttributes(string $customerId): ListPage<AttributeData>
```

<a id="entitlement-methods"></a>
### **Entitlement Methods**
```php
getEntitlement(string $entitlementId): EntitlementData
listEntitlements(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<EntitlementData>
createEntitlement(string $lookupKey, string $displayName): EntitlementData
updateEntitlement(string $entitlementId, string $displayName): EntitlementData
deleteEntitlement(string $entitlementId): bool
attachEntitlementProducts(string $entitlementId, array $productIds): EntitlementData
detachEntitlementProducts(string $entitlementId, array $productIds): EntitlementData
listEntitlementProducts(string $entitlementId): ListPage<ProductData>
```

<a id="offering-methods"></a>
### **Offering Methods**
```php
getOffering(string $offeringId): OfferingData
listOfferings(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<OfferingData>
createOffering(string $lookupKey, string $displayName, ?array $metadata): OfferingData
updateOffering(string $offeringId, ?string $displayName, ?bool $isCurrent, ?array $metadata): OfferingData
deleteOffering(string $offeringId): bool
```

<a id="package-methods"></a>
### **Package Methods**
```php
getPackage(string $packageId): PackageData
listPackages(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<PackageData>
createPackage(string $lookupKey, string $displayName, ?int $position): PackageData
updatePackage(string $packageId, ?string $displayName, ?int $position): PackageData
deletePackage(string $packageId): bool
listPackageProducts(string $packageId): ListPage<ProductData>
attachPackageProducts(string $packageId, array $productAssociationList): PackageData
detachPackageProducts(string $packageId, array $productIds): PackageData
```

<a id="product-methods"></a>
### **Product Methods**
```php
getProduct(string $productId): ProductData
listProducts(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<ProductData>
createProduct(string $storeIdentifier, string $appId, string $type, ?string $displayName): ProductData
deleteProduct(string $productId): bool
```

<a id="project-methods"></a>
### **Project Methods**
```php
listProjects(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<ProjectData>
```

<a id="purchase-methods"></a>
### **Purchase Methods**
```php
getPurchase(string $purchaseId): PurchaseData
listPurchaseEntitlements(string $purchaseId): ListPage<EntitlementData>
refundWebBillingPurchase(string $purchaseId): PurchaseData
searchPurchasesByIdentifier(string $storePurchaseIdentifier): ListPage<PurchaseData>
```

<a id="subscription-methods"></a>
### **Subscription Methods**
```php
getSubscription(string $subscriptionId): SubscriptionData
listSubscriptionEntitlements(string $subscriptionId): ListPage<EntitlementData>
listSubscriptionTransactions(string $subscriptionId): ListPage<TransactionData>
getSubscriptionCustomerPortalUrl(string $subscriptionId): Response
cancelWebBillingSubscription(string $subscriptionId): SubscriptionData
refundWebBillingSubscription(string $subscriptionId): SubscriptionData
refundPlayStoreSubscriptionTransaction(string $subscriptionId, string $transactionId): TransactionData
```

<a id="invoice-methods"></a>
### **Invoice Methods**
```php
listCustomerInvoices(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage<InvoiceData>
```

<a id="paywall-methods"></a>
### **Paywall Methods**
```php
createPaywall(string $offeringId): PaywallData
```

---