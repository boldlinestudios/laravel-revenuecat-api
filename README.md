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

#### 1) Endpoint-style (fluent)
```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCatClient as RevenueCat;

// Apps
$app = RevenueCat::apps()->get('app_id');
$apps = RevenueCat::apps()->list(10); // (int $limit = 20, ?string $startingAfter = null, array $extra = [])

// Customers
$customer = RevenueCat::customers()->get('customer_id');
$customers = RevenueCat::customers()->list(25);

// Other endpoints
$entitlements = RevenueCat::entitlements()->list();
$offerings = RevenueCat::offerings()->list();
$products = RevenueCat::products()->list();
$packages = RevenueCat::packages()->list();
$projects = RevenueCat::projects()->list(5);

```

#### 2) Convenience-style (direct)
These map 1:1 to common operations and return `Illuminate\Http\Client\Response`.

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCatClient as RevenueCat;

// Apps
$app = RevenueCat::getApp('app_id');
$apps = RevenueCat::getAppList(10);
$created = RevenueCat::createApp(['name' => 'My App', 'type' => 'app_store']);
RevenueCat::updateApp('app_id', ['name' => 'New Name']);
RevenueCat::deleteApp('app_id');

// Customers
$customer = RevenueCat::getCustomer('customer_id');
$customers = RevenueCat::getCustomerList(25);
$subs = RevenueCat::getCustomerSubscriptions('customer_id');
```

### Using Dependency Injection

```php
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient; // Single surface: endpoint + convenience

class SubscriptionController extends Controller
{
    public function __construct(private RevenueCatClient $client) {}

    public function show(string $userId)
    {
        // Endpoint-style
        $customer = $this->client->customers()->get($userId);

        // Or convenience-style
        $customer2 = $this->client->getCustomer($userId);

        return response()->json($customer->json());
    }
}
```

## Endpoint Examples

Below are examples for each endpoint using both styles:
- Endpoint-style: `RevenueCatClient::apps()->get('app_id')`
- Convenience-style: `RevenueCatClient::getApp('app_id')`

All examples use the facade alias for brevity:

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCatClient as RevenueCat;
```

### Apps
```php
// Get
$r = RevenueCat::apps()->get('app_id');
$r = RevenueCat::getApp('app_id');

// List
$r = RevenueCat::apps()->list(10);
$r = RevenueCat::getAppList(10);

// Create
$r = RevenueCat::apps()->create(['name' => 'My App', 'type' => 'app_store']);
$r = RevenueCat::createApp(['name' => 'My App', 'type' => 'app_store']);

// Update
$r = RevenueCat::apps()->update('app_id', ['name' => 'New Name']);
$r = RevenueCat::updateApp('app_id', ['name' => 'New Name']);

// Delete
$r = RevenueCat::apps()->delete('app_id');
$r = RevenueCat::deleteApp('app_id');

// StoreKit config
$r = RevenueCat::apps()->storeKitConfig('app_id');
$r = RevenueCat::getAppStoreKitConfig('app_id');

// Public API keys
$r = RevenueCat::apps()->listOfPublicKeys('app_id');
$r = RevenueCat::getAppPublicKeys('app_id');
```

### Customers
```php
// Get
$r = RevenueCat::customers()->get('customer_id');
$r = RevenueCat::getCustomer('customer_id');

// List
$r = RevenueCat::customers()->list(25);
$r = RevenueCat::getCustomerList(25);

// Create
$r = RevenueCat::customers()->create(['name' => 'Jane']);
$r = RevenueCat::createCustomer(['name' => 'Jane']);

// Update
$r = RevenueCat::customers()->update('customer_id', ['name' => 'Jane D.']);
$r = RevenueCat::updateCustomer('customer_id', ['name' => 'Jane D.']);

// Delete
$r = RevenueCat::customers()->delete('customer_id');
$r = RevenueCat::deleteCustomer('customer_id');

// Subscriptions
$r = RevenueCat::customers()->listOfSubscriptions('customer_id');
$r = RevenueCat::getCustomerSubscriptions('customer_id');

// Purchases
$r = RevenueCat::customers()->listOfPurchases('customer_id');
$r = RevenueCat::getCustomerPurchases('customer_id');

// Active entitlements
$r = RevenueCat::customers()->listOfActiveEntitlements('customer_id');
$r = RevenueCat::getCustomerActiveEntitlements('customer_id');

// Aliases
$r = RevenueCat::customers()->listOfAliases('customer_id');
$r = RevenueCat::getCustomerAliases('customer_id');

// Virtual currency balances
$r = RevenueCat::customers()->listOfVirtualCurrencyBalances('customer_id');
$r = RevenueCat::getCustomerVirtualCurrencyBalances('customer_id');

// Attributes
$r = RevenueCat::customers()->listOfAttributes('customer_id');
$r = RevenueCat::getCustomerAttributes('customer_id');
```

### Entitlements
```php
// Get
$r = RevenueCat::entitlements()->get('entitlement_id');
$r = RevenueCat::getEntitlement('entitlement_id');

// List
$r = RevenueCat::entitlements()->list(10);
$r = RevenueCat::getEntitlementList(10);

// Create
$r = RevenueCat::entitlements()->create(['identifier' => 'premium']);
$r = RevenueCat::createEntitlement(['identifier' => 'premium']);

// Update
$r = RevenueCat::entitlements()->update('entitlement_id', ['identifier' => 'pro']);
$r = RevenueCat::updateEntitlement('entitlement_id', ['identifier' => 'pro']);

// Delete
$r = RevenueCat::entitlements()->delete('entitlement_id');
$r = RevenueCat::deleteEntitlement('entitlement_id');

// Products for entitlement
$r = RevenueCat::entitlements()->listOfProducts('entitlement_id');
$r = RevenueCat::getEntitlementProducts('entitlement_id');
```

### Offerings
```php
// Get
$r = RevenueCat::offerings()->get('offering_id');
$r = RevenueCat::getOffering('offering_id');

// List
$r = RevenueCat::offerings()->list(10);
$r = RevenueCat::getOfferingList(10);

// Create
$r = RevenueCat::offerings()->create(['name' => 'Basic']);
$r = RevenueCat::createOffering(['name' => 'Basic']);

// Update
$r = RevenueCat::offerings()->update('offering_id', ['name' => 'Pro']);
$r = RevenueCat::updateOffering('offering_id', ['name' => 'Pro']);

// Delete
$r = RevenueCat::offerings()->delete('offering_id');
$r = RevenueCat::deleteOffering('offering_id');
```

### Packages
```php
// Get
$r = RevenueCat::packages()->get('package_id');
$r = RevenueCat::getPackage('package_id');

// List
$r = RevenueCat::packages()->list(10);
$r = RevenueCat::getPackageList(10);

// Create
$r = RevenueCat::packages()->create(['name' => 'Gold']);
$r = RevenueCat::createPackage(['name' => 'Gold']);

// Update
$r = RevenueCat::packages()->update('package_id', ['name' => 'Platinum']);
$r = RevenueCat::updatePackage('package_id', ['name' => 'Platinum']);

// Delete
$r = RevenueCat::packages()->delete('package_id');
$r = RevenueCat::deletePackage('package_id');

// Products in a package
$r = RevenueCat::packages()->listOfProducts('package_id');
$r = RevenueCat::getPackageProducts('package_id');
```

### Products
```php
// Get
$r = RevenueCat::products()->get('product_id');
$r = RevenueCat::getProduct('product_id');

// List
$r = RevenueCat::products()->list(10);
$r = RevenueCat::getProductList(10);

// Create
$r = RevenueCat::products()->create(['name' => 'Monthly']);
$r = RevenueCat::createProduct(['name' => 'Monthly']);

// Update
$r = RevenueCat::products()->update('product_id', ['name' => 'Annual']);
$r = RevenueCat::updateProduct('product_id', ['name' => 'Annual']);

// Delete
$r = RevenueCat::products()->delete('product_id');
$r = RevenueCat::deleteProduct('product_id');
```

### Projects
```php
// List projects (not project-scoped)
$r = RevenueCat::projects()->list(5);
$r = RevenueCat::getProjectList(5);
```

### Purchases
```php
// Get purchase
$r = RevenueCat::purchases()->get('purchase_id');
$r = RevenueCat::getPurchase('purchase_id');

// Entitlements for a purchase
$r = RevenueCat::purchases()->listOfEntitlements('purchase_id');
$r = RevenueCat::getPurchaseEntitlements('purchase_id');
```

### Subscriptions
```php
// Get subscription
$r = RevenueCat::subscriptions()->get('subscription_id');
$r = RevenueCat::getSubscription('subscription_id');

// Entitlements for a subscription
$r = RevenueCat::subscriptions()->listOfEntitlements('subscription_id');
$r = RevenueCat::getSubscriptionEntitlements('subscription_id');

// Transactions for a subscription
$r = RevenueCat::subscriptions()->listOfTransactions('subscription_id');
$r = RevenueCat::getSubscriptionTransactions('subscription_id');

// Customer portal URL
$r = RevenueCat::subscriptions()->getCustomerPortalUrl('subscription_id');
$r = RevenueCat::getSubscriptionCustomerPortalUrl('subscription_id');

// Cancel web billing subscription
$r = RevenueCat::subscriptions()->cancelWebBillingSubscription('subscription_id');
$r = RevenueCat::cancelWebBillingSubscription('subscription_id');

// Refund web billing subscription
$r = RevenueCat::subscriptions()->refundWebBillingSubscription('subscription_id');
$r = RevenueCat::refundWebBillingSubscription('subscription_id');
```

## Response Handling

All methods return an `Illuminate\Http\Client\Response` object, which provides methods like:

```php
$response = \BoldlineStudios\RevenueCatApi\Facades\RevenueCatClient::getCustomer('customer_id');

if ($response->successful()) {
    $data = $response->json();
    // Process the data
} else {
    $error = $response->json();
    // Handle the error (custom exceptions are thrown on non-2xx responses if you use the client directly)
}
```

### Error Handling

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
    $response = \BoldlineStudios\RevenueCatApi\Facades\RevenueCatClient::getCustomer('customer_id');
    $data = $response->json();
} catch (RateLimitException $e) {
    // Inspect rate limit headers
    $retryAt = $e->getReset();
    // sleep/retry logic here
} catch (ApiResponseException $e) {
    // Common fields from RevenueCat error payload
    $status = $e->getStatusCode();
    $type = $e->getErrorType();         // e.g. authentication_error, resource_missing
    $docs = $e->getDocsUrl();           // e.g. https://errors.rev.cat/authentication-error
    $details = $e->getDetails();        // full error payload as array
    // handle/log as needed
}
```

Notes:
- 429 errors expose `getLimit()`, `getRemaining()`, and `getReset()` on the exception.
- Error payload fields follow RevenueCat docs: `object`, `type`, `message`, `retryable`, `doc_url`, `param`, `backoff_ms`.

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
