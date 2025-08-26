# Laravel RevenueCat API

A Laravel package that provides a clean, fully-typed wrapper for the RevenueCat API v2.  
Built for production apps, using DTOs, exceptions, and first-class Laravel integration.

> **Note:** This is not an official package of RevenueCat or Laravel.

## Quick Example

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$customer = RevenueCat::getCustomer('cust_123');

echo $customer->getId();    // "cust_123"
echo $customer->getEmail(); // "me@example.com"
```

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

#### 1) Endpoint-style
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

#### 2) Convenience-style
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

## Endpoint Examples

For the full catalog of examples, see [ENDPOINTS.md](ENDPOINTS.md).

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
