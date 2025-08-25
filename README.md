# Laravel RevenueCat API

A Laravel package that provides a clean wrapper for the RevenueCat API v2.

> **Note:** This is not an official package of RevenueCat or Laravel.

## Installation

You can install the package via composer:

```bash
composer require boldlinestudios/laravel-revenuecat-api
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=revenuecat-api-config
```

Add the following environment variables to your `.env` file:

```env
REVENUECAT_API_KEY=your_api_key_here
REVENUECAT_PROJECT_ID=your_project_id
REVENUECAT_BASE_URL=https://api.revenuecat.com/v2
REVENUECAT_TIMEOUT=30
```

## Usage

### Using the Facade

#### 1) Endpoint-style (fluent, returns DTOs)
```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Apps
$app = RevenueCat::apps()->get('app_id'); // AppData
$appsPage = RevenueCat::apps()->list(10); // ListPage<AppData>

// Access DTO fields
$app->getId();
$app->getName();
foreach ($appsPage->items() as $a) { /* $a is AppData */ }

// Customers
$customer = RevenueCat::customers()->get('customer_id'); // CustomerData
$customers = RevenueCat::customers()->list(25); // ListPage<CustomerData>

// Other endpoints
$entitlements = RevenueCat::entitlements()->list(); // ListPage<EntitlementData>
$offerings = RevenueCat::offerings()->list(); // ListPage<OfferingData>
$products = RevenueCat::products()->list(); // ListPage<ProductData>
$packages = RevenueCat::packages()->list(); // ListPage<PackageData>
$projects = RevenueCat::projects()->list(5); // ListPage<ProjectData>
```

#### 2) Convenience-style (direct, returns DTOs/ListPage where applicable)
These map 1:1 to common operations and generally return DTOs. Some non-resource calls still return `Illuminate\Http\Client\Response`.

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Apps
$app = RevenueCat::getApp('app_id'); // AppData
$apps = RevenueCat::getAppList(10); // ListPage<AppData>
$created = RevenueCat::createApp(['name' => 'My App', 'type' => 'app_store']); // AppData
$updated = RevenueCat::updateApp('app_id', ['name' => 'New Name']); // AppData
$deleted = RevenueCat::deleteApp('app_id'); // bool

// Customers
$customer = RevenueCat::getCustomer('customer_id'); // CustomerData
// Note: for typed customer list, prefer endpoint style
$subs = RevenueCat::getCustomerSubscriptions('customer_id'); // Response
```

### Using Dependency Injection

```php
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient; // Single surface: endpoint + convenience

class SubscriptionController extends Controller
{
    public function __construct(private RevenueCatClient $client) {}

    public function show(string $userId)
    {
        // Endpoint-style (CustomerData)
        $customer = $this->client->customers()->get($userId);

        // Or convenience-style (CustomerData)
        $customer2 = $this->client->getCustomer($userId);

        return response()->json($customer->toArray());
    }
}
```

## Endpoint Examples (DTOs)

Below are examples for each endpoint using both styles with DTOs:
- Endpoint-style: returns DTOs/ListPage
- Convenience-style: returns DTOs/ListPage for common operations; some utility endpoints return Response

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
```

### Apps
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

### Customers
```php
// Get (CustomerData)
$customer = RevenueCat::customers()->get('customer_id');
$customer = RevenueCat::getCustomer('customer_id');

// List (ListPage<CustomerData>)
$customers = RevenueCat::customers()->list(25);

// Create (CustomerData)
$created = RevenueCat::customers()->create([
  'id' => 'customer_id',
  'attributes' => [['name' => '$email', 'value' => 'me@example.com']],
]);

// Delete (bool)
$deleted = RevenueCat::customers()->delete('customer_id');
$deleted = RevenueCat::deleteCustomer('customer_id');

// Subscriptions (Response)
$resp = RevenueCat::customers()->listOfSubscriptions('customer_id');
$resp = RevenueCat::getCustomerSubscriptions('customer_id');
```

### Entitlements
```php
// Get (EntitlementData)
$ent = RevenueCat::entitlements()->get('entitlement_id');
$ent = RevenueCat::getEntitlement('entitlement_id');

// List (ListPage<EntitlementData>)
$ents = RevenueCat::entitlements()->list(10);
$ents = RevenueCat::getEntitlementList(10);

// Create/Update (EntitlementData)
$created = RevenueCat::entitlements()->create(['lookup_key' => 'premium', 'display_name' => 'Premium']);
$updated = RevenueCat::entitlements()->update('entitlement_id', ['display_name' => 'Pro']);

// Delete (bool)
$deleted = RevenueCat::entitlements()->delete('entitlement_id');
$deleted = RevenueCat::deleteEntitlement('entitlement_id');

// Products for entitlement (Response)
$resp = RevenueCat::entitlements()->listOfProducts('entitlement_id');
$resp = RevenueCat::getEntitlementProducts('entitlement_id');
```

### Offerings
```php
// Get (OfferingData)
$offering = RevenueCat::offerings()->get('offering_id');
$offering = RevenueCat::getOffering('offering_id');

// List (ListPage<OfferingData>)
$offerings = RevenueCat::offerings()->list(10);
$offerings = RevenueCat::getOfferingList(10);

// Create/Update (OfferingData)
$created = RevenueCat::offerings()->create(['lookup_key' => 'basic', 'display_name' => 'Basic']);
$updated = RevenueCat::offerings()->update('offering_id', ['display_name' => 'Pro']);

// Delete (bool)
$deleted = RevenueCat::offerings()->delete('offering_id');
$deleted = RevenueCat::deleteOffering('offering_id');
```

### Packages
```php
// Get (PackageData)
$pkg = RevenueCat::packages()->get('package_id');
$pkg = RevenueCat::getPackage('package_id');

// List (ListPage<PackageData>)
$pkgs = RevenueCat::packages()->list(10);
$pkgs = RevenueCat::getPackageList(10);

// Create/Update (PackageData)
$created = RevenueCat::packages()->create(['lookup_key' => 'gold', 'display_name' => 'Gold', 'position' => 1]);
$updated = RevenueCat::packages()->update('package_id', ['display_name' => 'Platinum', 'position' => 2]);

// Delete (bool)
$deleted = RevenueCat::packages()->delete('package_id');
$deleted = RevenueCat::deletePackage('package_id');

// Products in a package (Response)
$resp = RevenueCat::packages()->listOfProducts('package_id');
$resp = RevenueCat::getPackageProducts('package_id');
```

### Products
```php
// Get (ProductData)
$product = RevenueCat::products()->get('product_id');
$product = RevenueCat::getProduct('product_id');

// List (ListPage<ProductData>)
$products = RevenueCat::products()->list(10);
$products = RevenueCat::getProductList(10);

// Create (ProductData)
$created = RevenueCat::products()->create([
  'store_identifier' => 'rc_1w_199',
  'app_id' => 'app_id',
  'type' => 'subscription',
]);

// Delete (bool)
$deleted = RevenueCat::products()->delete('product_id');
$deleted = RevenueCat::deleteProduct('product_id');
```

### Projects
```php
// List projects (ListPage<ProjectData>, not project-scoped)
$projects = RevenueCat::projects()->list(5);
$projects = RevenueCat::getProjectList(5);
```

### Purchases
```php
// Get purchase (PurchaseData)
$purchase = RevenueCat::purchases()->get('purchase_id');
$purchase = RevenueCat::getPurchase('purchase_id');

// Entitlements for a purchase (Response)
$resp = RevenueCat::purchases()->listOfEntitlements('purchase_id');
$resp = RevenueCat::getPurchaseEntitlements('purchase_id');
```

### Subscriptions
```php
// Get subscription (SubscriptionData)
$sub = RevenueCat::subscriptions()->get('subscription_id');
$sub = RevenueCat::getSubscription('subscription_id');

// Entitlements for a subscription (Response)
$resp = RevenueCat::subscriptions()->listOfEntitlements('subscription_id');
$resp = RevenueCat::getSubscriptionEntitlements('subscription_id');

// Transactions for a subscription (Response)
$resp = RevenueCat::subscriptions()->listOfTransactions('subscription_id');
$resp = RevenueCat::getSubscriptionTransactions('subscription_id');

// Customer portal URL (Response)
$resp = RevenueCat::subscriptions()->getCustomerPortalUrl('subscription_id');
$resp = RevenueCat::getSubscriptionCustomerPortalUrl('subscription_id');

// Cancel web billing subscription (Response)
$resp = RevenueCat::subscriptions()->cancelWebBillingSubscription('subscription_id');
$resp = RevenueCat::cancelWebBillingSubscription('subscription_id');

// Refund web billing subscription (Response)
$resp = RevenueCat::subscriptions()->refundWebBillingSubscription('subscription_id');
$resp = RevenueCat::refundWebBillingSubscription('subscription_id');
```

## Response and DTO Handling

- Endpoint-style returns DTOs like `AppData`, `CustomerData`, etc., or `ListPage<T>` for paginated lists.
- Convenience-style returns DTOs/ListPage for common operations; some utility endpoints return `Response`.
- DTOs expose typed getters and `toArray()`. Access the raw payload via `getRaw()`.
- `ListPage<T>` exposes `items(): array<int,T>`, `nextCursor()`, `url()`, and `raw(): Response`.
- Need the raw response? Use the raw methods on endpoints (e.g., `getRaw`, `listRaw`, `createRaw`, `updateRaw`, `deleteRaw`).

## Error Handling

This package throws descriptive exceptions for non-2xx responses based on RevenueCat’s error model. Catch specific types when you need granular handling, or the base type to handle all:

```php
use BoldlineStudios\RevenueCatApi\Exceptions\BadRequestException;       // 400
use BoldlineStudios\RevenueCatApi\Exceptions\AuthenticationException;   // 401
use BoldlineStudios\RevenueCatApi\Exceptions\AuthorizationException;    // 403
use BoldlineStudios\RevenueCatApi\Exceptions\NotFoundException;         // 404
use BoldlineStudios\RevenueCatApi\Exceptions\ConflictException;         // 409
use BoldlineStudios\RevenueCatApi\Exceptions\ValidationException;       // 422
use BoldlineStudios\RevenueCatApi\Exceptions\RateLimitException;        // 429
use BoldlineStudios\RevenueCatApi\Exceptions\ServerErrorException;      // 5xx
use BoldlineStudios\RevenueCatApi\Exceptions\ApiResponseException;      // fallback

try {
    $customer = \BoldlineStudios\RevenueCatApi\Facades\RevenueCat::getCustomer('customer_id');
    // work with the CustomerData DTO
} catch (RateLimitException $e) {
    // Inspect rate limit headers
    $retryAt = $e->getReset();
} catch (ApiResponseException $e) {
    // Common fields from RevenueCat error payload
    $status = $e->getStatusCode();
    $type = $e->getErrorType();         // e.g. authentication_error, resource_missing
    $docs = $e->getDocsUrl();           // e.g. https://errors.rev.cat/authentication-error
    $details = $e->getDetails();        // full error payload as array
}
```

Notes:
- 429 errors expose `getLimit()`, `getRemaining()`, and `getReset()` on the exception.
- Error payload fields follow RevenueCat docs: `object`, `type`, `message`, `retryable`, `doc_url`, `param`, `backoff_ms`.

## Testing

```bash
./vendor/bin/pest
```

## Linting
```bash
./vendor/bin/pint
```

## Static Analysis
```bash
./vendor/bin/phpstan analyze
```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
