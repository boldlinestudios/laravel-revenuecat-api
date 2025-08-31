# Endpoint Examples

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
```

## Apps

#### Get
```php
// Signature: 
// apps()->get(string $appId): AppData

$app = RevenueCat::apps()->get('app_id');
$app = RevenueCat::getApp('app_id');
```

#### List
```php
// Signature: 
// apps()->list(int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = []): ListPage<AppData>

$apps = RevenueCat::apps()->list(10);
$apps = RevenueCat::getAppList(10);
```

#### Create
```php
// Signature: 
// apps()->create(string $name, string $type, array<string, array<string, mixed>> $storeConfig): AppData

$app = RevenueCat::apps()->create('My App', 'app_store', [
        'app_store' => [
            'bundle_id' => 'com.example.app'
        ]
    ]);
$app = RevenueCat::createApp('My App', 'app_store', [
    'app_store' => [
        'bundle_id' => 'com.example.app'
    ]
]);
```

#### Update
```php
// Signature: 
// apps()->update(string $appId, array<string, mixed> $data): AppData

$updated = RevenueCat::apps()->update('app_id', [
  'name' => 'New Name',
  // optional provider config keys allowed
  // 'app_store' => ['bundle_id' => 'com.example.app']
]);
$updated = RevenueCat::updateApp('app_id', [
  'name' => 'New Name',
  // 'app_store' => ['bundle_id' => 'com.example.app']
]);
```

#### Delete
```php
// Signature: apps()->delete(string $appId): bool

$deleted = RevenueCat::apps()->delete('app_id');
$deleted = RevenueCat::deleteApp('app_id');
```

#### StoreKit config
```php
// Signature: apps()->storeKitConfig(string $appId): Illuminate\Http\Client\Response

$resp = RevenueCat::apps()->storeKitConfig('app_id');
$resp = RevenueCat::getAppStoreKitConfig('app_id');
```

#### Public API keys
```php
// Signature: apps()->listOfPublicKeys(string $appId): Illuminate\Http\Client\Response

$resp = RevenueCat::apps()->listOfPublicKeys('app_id');
$resp = RevenueCat::getAppPublicKeys('app_id');
```

## Customers

#### Get
```php
// Signature:
// customers()->get(string $customerId): CustomerData

$customer = RevenueCat::customers()->get('customer_id');
$customer = RevenueCat::getCustomer('customer_id');
```

#### List
```php
// Signature:
// customers()->list(int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = []): ListPage<CustomerData>

$customers = RevenueCat::customers()->list(25);
```

#### Create
```php
// Signature:
// customers()->create(string $id, list<array{name: string, value: string}> $attributes): CustomerData

$created = RevenueCat::customers()->create('customer_id', [
  ['name' => '$email', 'value' => 'me@example.com'],
]);
```

#### Delete
```php
// Signature:
// customers()->delete(string $customerId): bool

$deleted = RevenueCat::customers()->delete('customer_id');
$deleted = RevenueCat::deleteCustomer('customer_id');
```

#### Subscriptions
```php
// Signature:
// customers()->listOfSubscriptions(string $customerId, int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = []): ListPage<SubscriptionData>

$subs = RevenueCat::customers()->listOfSubscriptions('customer_id');
$subs = RevenueCat::getCustomerSubscriptions('customer_id');
```

#### Purchases
```php
// Signature:
// customers()->listOfPurchases(string $customerId, int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = []): ListPage<PurchaseData>

$purchases = RevenueCat::customers()->listOfPurchases('customer_id');
$purchases = RevenueCat::getCustomerPurchases('customer_id');
```

#### Active entitlements
```php
// Signature:
// customers()->listOfActiveEntitlements(string $customerId): ListPage<CustomerActiveEntitlementData>

$ents = RevenueCat::customers()->listOfActiveEntitlements('customer_id');
$ents = RevenueCat::getCustomerActiveEntitlements('customer_id');
```

#### Aliases
```php
// Signature:
// customers()->listOfAliases(string $customerId, int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = []): ListPage<CustomerAliasData>

$aliases = RevenueCat::customers()->listOfAliases('customer_id');
$aliases = RevenueCat::getCustomerAliases('customer_id');
```

#### Virtual currency balances
```php
// Signature:
// customers()->listOfVirtualCurrencyBalances(string $customerId, int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = []): ListPage<CustomerVirtualCurrencyBalanceData>

$balances = RevenueCat::customers()->listOfVirtualCurrencyBalances('customer_id');
$balances = RevenueCat::getCustomerVirtualCurrencyBalances('customer_id');
```

#### Attributes
```php
// Signature:
// customers()->listOfAttributes(string $customerId, int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = []): ListPage<CustomerAttributeData>

$attrs = RevenueCat::customers()->listOfAttributes('customer_id');
$attrs = RevenueCat::getCustomerAttributes('customer_id');
```

#### Set attributes
```php
// Signature:
// customers()->setAttributes(string $customerId, list<array{name: string, value: string}> $attributes): ListPage<CustomerAttributeData>

$attrs = RevenueCat::customers()->setAttributes('customer_id', [
  ['name' => '$email', 'value' => 'support@revenuecat.com'],
]);
```

## Entitlements

#### Get
```php
// Signature:
// entitlements()->get(string $entitlementId): EntitlementData

$ent = RevenueCat::entitlements()->get('entitlement_id');
$ent = RevenueCat::getEntitlement('entitlement_id');
```

#### List
```php
// Signature:
// entitlements()->list(int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = []): ListPage<EntitlementData>

$ents = RevenueCat::entitlements()->list(10);
$ents = RevenueCat::getEntitlementList(10);
```

#### Create
```php
// Signature:
// entitlements()->create(string $lookupKey, string $displayName): EntitlementData

$created = RevenueCat::entitlements()->create('premium', 'Premium');
```

#### Update
```php
// Signature:
// entitlements()->update(string $entitlementId, string $displayName): EntitlementData

$updated = RevenueCat::entitlements()->update('entitlement_id', 'Pro');
```

#### Delete
```php
// Signature:
// entitlements()->delete(string $entitlementId): bool

$deleted = RevenueCat::entitlements()->delete('entitlement_id');
$deleted = RevenueCat::deleteEntitlement('entitlement_id');
```

#### Attach products
```php
// Signature:
// entitlements()->attachProducts(string $entitlementId, array<string> $productIds): EntitlementData

$entitlement = RevenueCat::entitlements()->attachProducts('entitlement_id', ['prod_1']);
$entitlement = RevenueCat::attachEntitlementProducts('entitlement_id', ['prod_1']);
```

#### Detach products
```php
// Signature:
// entitlements()->detachProducts(string $entitlementId, array<string> $productIds): EntitlementData

$entitlement = RevenueCat::entitlements()->detachProducts('entitlement_id', ['prod_1']);
$entitlement = RevenueCat::detachEntitlementProducts('entitlement_id', ['prod_1']);
```

#### Products
```php
// Signature:
// entitlements()->listOfProducts(string $entitlementId, int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = []): ListPage<ProductData>

$products = RevenueCat::entitlements()->listOfProducts('entitlement_id');
$products = RevenueCat::getEntitlementProducts('entitlement_id');
```

## Offerings

#### Get
```php
// Signature:
// offerings()->get(string $offeringId): OfferingData

$offering = RevenueCat::offerings()->get('offering_id');
$offering = RevenueCat::getOffering('offering_id');
```

#### List
```php
// Signature:
// offerings()->list(int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = []): ListPage<OfferingData>

$offerings = RevenueCat::offerings()->list(10);
$offerings = RevenueCat::getOfferingList(10);
```

#### Create
```php
// Signature:
// offerings()->create(string $lookupKey, string $displayName, ?array<string, mixed> $metadata = null): OfferingData
$created = RevenueCat::offerings()->create('basic', 'Basic', ['color' => 'blue']);
```

#### Update
```php
// Signature:
// offerings()->update(string $offeringId, ?string $displayName, ?bool $isCurrent, ?array<string, mixed> $metadata = null): OfferingData

$updated = RevenueCat::offerings()->update('offering_id', 'Pro', null, ['color' => 'green']);
```

#### Delete
```php
// Signature:
// offerings()->delete(string $offeringId): bool

$deleted = RevenueCat::offerings()->delete('offering_id');
$deleted = RevenueCat::deleteOffering('offering_id');
```

## Packages

#### Get
```php
// Signature:
// packages()->get(string $packageId): PackageData

$pkg = RevenueCat::packages()->get('package_id');
$pkg = RevenueCat::getPackage('package_id');
```

#### List
```php
// Signature:
// packages()->list(int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = []): ListPage<PackageData>

$pkgs = RevenueCat::packages()->list(10);
$pkgs = RevenueCat::getPackageList(10);
```

#### Create
```php
// Signature:
// packages()->create(string $lookupKey, string $displayName, ?int $position = null): PackageData

$created = RevenueCat::packages()->create('gold', 'Gold', 1);
```

#### Update
```php
// Signature:
// packages()->update(string $packageId, ?string $displayName, ?int $position = null): PackageData

$updated = RevenueCat::packages()->update('package_id', 'Platinum', 2);
```

#### Delete
```php
// Signature:
// packages()->delete(string $packageId): bool

$deleted = RevenueCat::packages()->delete('package_id');
$deleted = RevenueCat::deletePackage('package_id');
```

#### Products
```php
// Signature:
// packages()->listOfProducts(string $packageId, int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = []): ListPage<ProductData>

$products = RevenueCat::packages()->listOfProducts('package_id');
$products = RevenueCat::getPackageProducts('package_id');
```

## Products

#### Get
```php
// Signature:
// products()->get(string $productId): ProductData

$product = RevenueCat::products()->get('product_id');
$product = RevenueCat::getProduct('product_id');
```

#### List
```php
// Signature:
// products()->list(int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = []): ListPage<ProductData>

$products = RevenueCat::products()->list(10);
$products = RevenueCat::getProductList(10);
```

#### Create
```php
// Signature:
// products()->create(string $storeIdentifier, string $appId, string $type, ?string $displayName = null): ProductData

$created = RevenueCat::products()->create('rc_1w_199', 'app_id', 'subscription', 'Display Name');
```

#### Delete
```php
// Signature:
// products()->delete(string $productId): bool

$deleted = RevenueCat::products()->delete('product_id');
$deleted = RevenueCat::deleteProduct('product_id');
```

## Projects

#### List (not project-scoped)
```php
// Signature:
// projects()->list(int $limit = 20, ?string $startingAfter = null, array<string, mixed> $extra = []): ListPage<ProjectData>

$projects = RevenueCat::projects()->list(5);
$projects = RevenueCat::getProjectList(5);
```

## Purchases

#### Get
```php
// Signature:
// purchases()->get(string $purchaseId): PurchaseData

$purchase = RevenueCat::purchases()->get('purchase_id');
$purchase = RevenueCat::getPurchase('purchase_id');
```

#### Entitlements (Response)
```php
// Signature:
// purchases()->listOfEntitlements(string $purchaseId): Illuminate\Http\Client\Response

$resp = RevenueCat::purchases()->listOfEntitlements('purchase_id');
$resp = RevenueCat::getPurchaseEntitlements('purchase_id');
```

## Subscriptions

#### Get
```php
// Signature:
// subscriptions()->get(string $subscriptionId): SubscriptionData

$sub = RevenueCat::subscriptions()->get('subscription_id');
$sub = RevenueCat::getSubscription('subscription_id');
```

#### Entitlements (Response)
```php
// Signature:
// subscriptions()->listOfEntitlements(string $subscriptionId): Illuminate\Http\Client\Response

$resp = RevenueCat::subscriptions()->listOfEntitlements('subscription_id');
$resp = RevenueCat::getSubscriptionEntitlements('subscription_id');
```

#### Transactions (Response)
```php
// Signature:
// subscriptions()->listOfTransactions(string $subscriptionId): Illuminate\Http\Client\Response

$resp = RevenueCat::subscriptions()->listOfTransactions('subscription_id');
$resp = RevenueCat::getSubscriptionTransactions('subscription_id');
```

#### Customer portal URL (Response)
```php
// Signature:
// subscriptions()->getCustomerPortalUrl(string $subscriptionId): Illuminate\Http\Client\Response

$resp = RevenueCat::subscriptions()->getCustomerPortalUrl('subscription_id');
$resp = RevenueCat::getSubscriptionCustomerPortalUrl('subscription_id');
```

#### Cancel/Refund web billing (Response)
```php
// Signature:
// subscriptions()->cancelWebBillingSubscription(string $subscriptionId): Illuminate\Http\Client\Response
// subscriptions()->refundWebBillingSubscription(string $subscriptionId): Illuminate\Http\Client\Response

$resp = RevenueCat::subscriptions()->cancelWebBillingSubscription('subscription_id');
$resp = RevenueCat::cancelWebBillingSubscription('subscription_id');
$resp = RevenueCat::subscriptions()->refundWebBillingSubscription('subscription_id');
$resp = RevenueCat::refundWebBillingSubscription('subscription_id');
```
