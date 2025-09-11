[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE.md)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/boldlinestudios/laravel-revenuecat-api.svg)](https://packagist.org/packages/boldlinestudios/laravel-revenuecat-api)


# Laravel RevenueCat API

A Laravel package for the RevenueCat API v2, providing typed methods and structured data objects.

> **Note:** This is not an official package of RevenueCat or Laravel.

## Quick Example

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$subscription = RevenueCat::getSubscription('sub1ab2c3d4e5');

echo $subscription->getCustomerId();    // "19b8de26-77c1-49f1-aa18-019a391603e2"
echo $subscription->getProductId();     // "prod_1ab2c3d4e5"
echo $subscription->givesAccess();      // true
```

For the full catalog of examples with code, see [ENDPOINTS.md](ENDPOINTS.md).
For a complete reference of all API response data structures, see [DATA.md](DATA.md).

## Installation

You can install the package via composer:

```bash
composer require boldlinestudios/laravel-revenuecat-api
```

## Requirements

- PHP 8.2+
- Laravel 11+

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

You can call methods in two ways. Both return the same results, so use whichever feels clearer.

#### 1) Endpoint-style
Organized by resource, similar to the REST API.

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$customer = RevenueCat::customers()->get('customer_id'); // CustomerData
$customers = RevenueCat::customers()->all(25); // ListPage<CustomerData>

foreach ($customers->items() as $c) { /* $c is CustomerData */ }
```

#### 2) Convenience-style
Direct shortcut methods for common operations.

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$entitlement = RevenueCat::getEntitlement('entitlement_id'); // EntitlementData
$entitlements = RevenueCat::listEntitlements(10); // ListPage<EntitlementData>
$newEntitlement = RevenueCat::createEntitlement('premium', 'Premium access to all features'); // EntitlementData
```
## Endpoints

Available endpoints with full documentation and examples:

- [Apps](docs/endpoints/apps.md)
- [Customers](docs/endpoints/customers.md)
- [Entitlements](docs/endpoints/entitlements.md)
- [Invoices](docs/endpoints/invoices.md)
- [Offerings](docs/endpoints/offerings.md)
- [Packages](docs/endpoints/packages.md)
- [Paywalls](docs/endpoints/paywalls.md)
- [Products](docs/endpoints/products.md)
- [Projects](docs/endpoints/projects.md)
- [Purchases](docs/endpoints/purchases.md)
- [Subscriptions](docs/endpoints/subscriptions.md)

## Response and Data Handling

- Most methods return **data objects** ([`AppData`](DATA.md#appdata), [`CustomerData`](DATA.md#customerdata), etc.).
- List endpoints return a **`ListPage<T>`** ([`ListPage<T>`](DATA.md#listpage)) wrapper for pagination.

See the full data object catalog [DATA.md](DATA.md).

### Data Objects

Data objects expose typed getters and a `toArray()` method:

```php
$product = RevenueCat::getProduct('prod_123');

echo $product->getId();
echo $product->getType();

return $product->toArray();
```

See [`ProductData`](DATA.md#productdata) for all available methods.

### ListPage & Pagination

See [`ListPage<T>`](DATA.md#listpage) documentation in [DATA.md](DATA.md).

List methods return a `ListPage<T>` that handles pagination automatically:

```php
// Paginate through all customers
$allCustomers = [];
$cursor = null;
$pageNumber = 1;

do {
    // Get current page (20 customers per page)
    $page = RevenueCat::listCustomers(20, $cursor);

    echo "Page {$pageNumber}: " . count($page->items()) . " customers\n";

    // Process customers on this page
    foreach ($page->items() as $customer) {
        echo $customer->getId();
        echo $customer->getLastSeenPlatform();
        $allCustomers[] = $customer;  // Collect all customers
    }

    // See [CustomerData](DATA.md#customerdata) for all available methods

    // Get cursor for next page
    $cursor = $page->nextCursor();
    $pageNumber++;

} while ($cursor); // Continue until no more pages

echo "Total customers found: " . count($allCustomers);


```

### Advanced Pagination

```php
// Custom page size and starting point
$page = RevenueCat::listCustomers(100, 'cursor_123');


```
## Error Handling

Non-2xx responses throw typed exceptions matching RevenueCat’s error model:

- `BadRequestException` (400)  
- `AuthenticationException` (401)  
- `AuthorizationException` (403)  
- `NotFoundException` (404)  
- `ConflictException` (409)  
- `ValidationException` (422)  
- `RateLimitException` (429)  
- `ServerErrorException` (5xx)  
- `ApiResponseException` (fallback for other errors)

## Testing

```bash
composer test
```

## Linting
```bash
composer lint
```

## Static Analysis
```bash
composer stan
```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

Released under the [MIT license](LICENSE.md).
