# Laravel RevenueCat API

A Laravel package that provides a clean and simple wrapper for the RevenueCat API 2.0.

> **Note:** This is not an official package of RevenueCat or Laravel. It is a community-maintained wrapper package.

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
REVENUECAT_BASE_URL=https://api.revenuecat.com/v2
REVENUECAT_TIMEOUT=30
REVENUECAT_RETRY_ATTEMPTS=3
REVENUECAT_RETRY_DELAY=1
```

## Usage

### Using the Facade

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCatApi;

// Get subscriber information
$subscriber = RevenueCatApi::getSubscriber('user123');

// Get subscriber entitlements
$entitlements = RevenueCatApi::getSubscriberEntitlements('user123');

// Grant promotional entitlement
$response = RevenueCatApi::grantPromotionalEntitlement('user123', [
    'entitlement_identifier' => 'premium',
    'duration' => 'month',
    'start_time' => '2024-01-01T00:00:00Z'
]);

// Get products
$products = RevenueCatApi::getProducts();

// Get offerings
$offerings = RevenueCatApi::getOfferings();
```

### Using Dependency Injection

```php
use BoldlineStudios\RevenueCatApi\Services\RevenueCatApiService;

class SubscriptionController extends Controller
{
    public function __construct(
        private RevenueCatApiService $revenueCat
    ) {}

    public function show(string $userId)
    {
        $subscriber = $this->revenueCat->getSubscriber($userId);
        
        return response()->json($subscriber->json());
    }
}
```

### Custom Requests

```php
// Make a custom API request
$response = RevenueCatApi::request('POST', '/v2/subscribers/user123/entitlements', [
    'entitlement_identifier' => 'premium',
    'duration' => 'month'
]);
```

## Available Methods

- `getSubscriber(string $appUserId)` - Get subscriber information
- `getSubscriberEntitlements(string $appUserId)` - Get subscriber entitlements
- `grantPromotionalEntitlement(string $appUserId, array $data)` - Grant promotional entitlement
- `revokePromotionalEntitlement(string $appUserId, string $entitlementId)` - Revoke promotional entitlement
- `getProducts()` - Get all products
- `getOfferings()` - Get all offerings
- `request(string $method, string $endpoint, array $data = [])` - Make custom API requests

## Response Handling

All methods return an `Illuminate\Http\Client\Response` object, which provides methods like:

```php
$response = RevenueCatApi::getSubscriber('user123');

if ($response->successful()) {
    $data = $response->json();
    // Process the data
} else {
    $error = $response->json();
    // Handle the error
}
```

## Models

The package includes model classes for common RevenueCat entities:

- `Subscriber` - Represents a RevenueCat subscriber
- `Entitlement` - Represents a subscriber entitlement

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
