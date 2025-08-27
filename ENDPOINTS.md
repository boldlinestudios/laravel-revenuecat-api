# Endpoint Examples (DTOs)

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
```

## Apps
```php
// Get (AppData)
$app = RevenueCat::apps()->get('app_id');
$app = RevenueCat::getApp('app_id');

// List (ListPage<AppData>)
$apps = RevenueCat::apps()->list(10);
$apps = RevenueCat::getAppList(10);

// Create (AppData)
$created = RevenueCat::apps()->create(['name' => 'My App', 'type' => 'app_store']);
$created = RevenueCat::createApp(['name' => 'My App', 'type' => 'app_store']);

// Update (AppData)
$updated = RevenueCat::apps()->update('app_id', ['name' => 'New Name']);
$updated = RevenueCat::updateApp('app_id', ['name' => 'New Name']);

// Delete (bool)
$deleted = RevenueCat::apps()->delete('app_id');
$deleted = RevenueCat::deleteApp('app_id');

// StoreKit config (Response)
$resp = RevenueCat::apps()->storeKitConfig('app_id');
$resp = RevenueCat::getAppStoreKitConfig('app_id');

// Public API keys (Response)
$resp = RevenueCat::apps()->listOfPublicKeys('app_id');
$resp = RevenueCat::getAppPublicKeys('app_id');
```

## Customers
```php
// Get (CustomerData)
$customer = RevenueCat::customers()->get('customer_id');
$customer = RevenueCat::getCustomer('customer_id');

// List (ListPage<CustomerData>)
$customers = RevenueCat::customers()->list(25);

// Create (CustomerData)
$created = RevenueCat::customers()->create('customer_id', [
  ['name' => '$email', 'value' => 'me@example.com'],
]);

// Delete (bool)
$deleted = RevenueCat::customers()->delete('customer_id');
$deleted = RevenueCat::deleteCustomer('customer_id');

// Subscriptions (Response)
$resp = RevenueCat::customers()->listOfSubscriptions('customer_id');
$resp = RevenueCat::getCustomerSubscriptions('customer_id');

// Purchases (Response)
$resp = RevenueCat::customers()->listOfPurchases('customer_id');
$resp = RevenueCat::getCustomerPurchases('customer_id');

// Active entitlements (Response)
$resp = RevenueCat::customers()->listOfActiveEntitlements('customer_id');
$resp = RevenueCat::getCustomerActiveEntitlements('customer_id');

// Aliases (Response)
$resp = RevenueCat::customers()->listOfAliases('customer_id');
$resp = RevenueCat::getCustomerAliases('customer_id');

// Virtual currency balances (Response)
$resp = RevenueCat::customers()->listOfVirtualCurrencyBalances('customer_id');
$resp = RevenueCat::getCustomerVirtualCurrencyBalances('customer_id');

// Attributes (Response)
$resp = RevenueCat::customers()->listOfAttributes('customer_id');
$resp = RevenueCat::getCustomerAttributes('customer_id');
```

## Entitlements
```php
// Get (EntitlementData)
$ent = RevenueCat::entitlements()->get('entitlement_id');
$ent = RevenueCat::getEntitlement('entitlement_id');

// List (ListPage<EntitlementData>)
$ents = RevenueCat::entitlements()->list(10);
$ents = RevenueCat::getEntitlementList(10);

// Create/Update (EntitlementData)
$created = RevenueCat::entitlements()->create('premium', 'Premium');
$updated = RevenueCat::entitlements()->update('entitlement_id', 'Pro');

// Delete (bool)
$deleted = RevenueCat::entitlements()->delete('entitlement_id');
$deleted = RevenueCat::deleteEntitlement('entitlement_id');

// Products (Response)
$resp = RevenueCat::entitlements()->listOfProducts('entitlement_id');
$resp = RevenueCat::getEntitlementProducts('entitlement_id');
```

## Offerings
```php
// Get (OfferingData)
$offering = RevenueCat::offerings()->get('offering_id');
$offering = RevenueCat::getOffering('offering_id');

// List (ListPage<OfferingData>)
$offerings = RevenueCat::offerings()->list(10);
$offerings = RevenueCat::getOfferingList(10);

// Create/Update (OfferingData)
$created = RevenueCat::offerings()->create('basic', 'Basic', ['color' => 'blue']);
$updated = RevenueCat::offerings()->update('offering_id', 'Pro', null, ['color' => 'green']);

// Delete (bool)
$deleted = RevenueCat::offerings()->delete('offering_id');
$deleted = RevenueCat::deleteOffering('offering_id');
```

## Packages
```php
// Get (PackageData)
$pkg = RevenueCat::packages()->get('package_id');
$pkg = RevenueCat::getPackage('package_id');

// List (ListPage<PackageData>)
$pkgs = RevenueCat::packages()->list(10);
$pkgs = RevenueCat::getPackageList(10);

// Create/Update (PackageData)
$created = RevenueCat::packages()->create('gold', 'Gold', 1);
$updated = RevenueCat::packages()->update('package_id', 'Platinum', 2);

// Delete (bool)
$deleted = RevenueCat::packages()->delete('package_id');
$deleted = RevenueCat::deletePackage('package_id');

// Products in a package (Response)
$resp = RevenueCat::packages()->listOfProducts('package_id');
$resp = RevenueCat::getPackageProducts('package_id');
```

## Products
```php
// Get (ProductData)
$product = RevenueCat::products()->get('product_id');
$product = RevenueCat::getProduct('product_id');

// List (ListPage<ProductData>)
$products = RevenueCat::products()->list(10);
$products = RevenueCat::getProductList(10);

// Create (ProductData)
$created = RevenueCat::products()->create('rc_1w_199', 'app_id', 'subscription', 'Display Name');

// Delete (bool)
$deleted = RevenueCat::products()->delete('product_id');
$deleted = RevenueCat::deleteProduct('product_id');
```

## Projects
```php
// List (ListPage<ProjectData>, not project-scoped)
$projects = RevenueCat::projects()->list(5);
$projects = RevenueCat::getProjectList(5);
```

## Purchases
```php
// Get (PurchaseData)
$purchase = RevenueCat::purchases()->get('purchase_id');
$purchase = RevenueCat::getPurchase('purchase_id');

// Entitlements (Response)
$resp = RevenueCat::purchases()->listOfEntitlements('purchase_id');
$resp = RevenueCat::getPurchaseEntitlements('purchase_id');
```

## Subscriptions
```php
// Get (SubscriptionData)
$sub = RevenueCat::subscriptions()->get('subscription_id');
$sub = RevenueCat::getSubscription('subscription_id');

// Entitlements (Response)
$resp = RevenueCat::subscriptions()->listOfEntitlements('subscription_id');
$resp = RevenueCat::getSubscriptionEntitlements('subscription_id');

// Transactions (Response)
$resp = RevenueCat::subscriptions()->listOfTransactions('subscription_id');
$resp = RevenueCat::getSubscriptionTransactions('subscription_id');

// Customer portal URL (Response)
$resp = RevenueCat::subscriptions()->getCustomerPortalUrl('subscription_id');
$resp = RevenueCat::getSubscriptionCustomerPortalUrl('subscription_id');

// Cancel/Refund web billing (Response)
$resp = RevenueCat::subscriptions()->cancelWebBillingSubscription('subscription_id');
$resp = RevenueCat::cancelWebBillingSubscription('subscription_id');
$resp = RevenueCat::subscriptions()->refundWebBillingSubscription('subscription_id');
$resp = RevenueCat::refundWebBillingSubscription('subscription_id');
```
