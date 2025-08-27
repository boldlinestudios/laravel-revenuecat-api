[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE.md)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/boldlinestudios/laravel-revenuecat-api.svg)](https://packagist.org/packages/boldlinestudios/laravel-revenuecat-api)


# Laravel RevenueCat API

A package that provides a clean, fully-typed wrapper for the RevenueCat API v2.
It provides typed DTOs, exceptions, and is designed for Laravel apps.

> **Note:** This is not an official package of RevenueCat or Laravel.

## Why use this package?

- This package gives you typed objects so your IDE and static analysis can help.  

- Common operations are available as simple methods

- Error handling follows RevenueCat’s own model 

- The underlying response is available if you need full control.

## Quick Example

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$subscription = RevenueCat::getSubscription('sub1ab2c3d4e5');

echo $subscription->getCustomerId();    // "19b8de26-77c1-49f1-aa18-019a391603e2"
echo $subscription->getProductId();     // "prod_1ab2c3d4e5"
echo $subscription->givesAccess();      // true
```

For the full catalog of examples with code, see [ENDPOINTS.md](ENDPOINTS.md).

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
$customers = RevenueCat::customers()->list(25); // ListPage<CustomerData>

foreach ($customers->items() as $c) { /* $c is CustomerData */ }
```

#### 2) Convenience-style
Direct shortcut methods for common operations.

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$entitlement = RevenueCat::getEntitlement('entitlement_id'); // EntitlementData
$entitlements = RevenueCat::getEntitlementList(10); // ListPage<EntitlementData>
$newEntitlement = RevenueCat::createEntitlement('premium', 'Premium access to all features'); // EntitlementData
```
## Endpoints

Available endpoints:

- Apps
- Customers
- Entitlements
- Offerings
- Packages
- Products
- Projects
- Purchases
- Subscriptions

For the full catalog of examples with code, see [ENDPOINTS.md](ENDPOINTS.md).

## Response and DTO Handling

- Most methods return **DTOs** (`AppData`, `CustomerData`, etc.).  
- List endpoints return a **`ListPage<T>`** wrapper for pagination.  
- Some utility endpoints return the raw **`Response`**.

DTOs expose typed getters and a `toArray()` method:

```php
$product = RevenueCat::getProduct('prod_123');

echo $product->getId();
echo $product->getType();

return $product->toArray();
```

`ListPage<T>` exposes `items(): array<int,T>`, `nextCursor()`, `url()`, and `raw(): Response`.

```php
$subscriptions = RevenueCat::getSubscriptionList(10);
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

Released under the [MIT license](LICENSE.md).
