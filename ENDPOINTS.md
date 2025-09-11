# Endpoint Documentation Index

> **Note:** This document describes the Laravel wrapper methods. For the official RevenueCat API documentation, see [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2).


## 🚀 Quick Access to Endpoint Documentation

### Core Resources
- **[Apps](docs/endpoints/apps.md)** - Manage RevenueCat apps and store configurations
- **[Customers](docs/endpoints/customers.md)** - Customer management with attributes and relationships
- **[Subscriptions](docs/endpoints/subscriptions.md)** - Subscription lifecycle and billing management
- **[Products](docs/endpoints/products.md)** - Product catalog and store integration

### Organization & Structure
- **[Projects](docs/endpoints/projects.md)** - Top-level project containers
- **[Offerings](docs/endpoints/offerings.md)** - Product collections presented to customers
- **[Packages](docs/endpoints/packages.md)** - Product groupings within offerings

### Transactions & Billing
- **[Purchases](docs/endpoints/purchases.md)** - Completed transactions and refunds
- **[Invoices](docs/endpoints/invoices.md)** - Customer billing history

### Advanced Features
- **[Entitlements](docs/endpoints/entitlements.md)** - Access control and feature management
- **[Paywalls](docs/endpoints/paywalls.md)** - Visual purchase interfaces

## 📖 Documentation Features

Each endpoint documentation file includes:
- ✅ **Complete method reference** - All available operations
- ✅ **Parameter details** - Required/optional parameters with descriptions
- ✅ **Working examples** - Both endpoint-style and convenience-style usage
- ✅ **Related data types** - Links to relevant DTOs and structures
- ✅ **Best practices** - Tips for common use cases

## 🔧 Usage Examples

### Quick Start
```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

// Get a customer
$customer = RevenueCat::getCustomer('cus_123');

// List subscriptions
$subscriptions = RevenueCat::listCustomerSubscriptions('cus_123');

// Create a product
$product = RevenueCat::createProduct(
    storeIdentifier: 'com.example.premium',
    appId: 'app_123',
    type: 'subscription'
);
```

### Advanced Usage
```php
// Paginated results
$page = RevenueCat::listCustomers(limit: 50, startingAfter: 'cursor_123');

// Error handling
try {
    $subscription = RevenueCat::getSubscription('sub_123');
} catch (NotFoundException $e) {
    // Handle missing subscription
}
```

## 📚 Additional Resources

- **[DATA.md](DATA.md)** - Complete data object reference
- **[README.md](README.md)** - Installation and setup guide
- **[CONTRIBUTING.md](CONTRIBUTING.md)** - Development guidelines

---

*This index serves as a quick reference. For detailed implementation guides, please visit the individual endpoint documentation files linked above.*