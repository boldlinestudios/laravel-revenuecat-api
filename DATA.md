# Data Objects

This document provides documentation for all data objects in this package. Data objects are strongly-typed representations of API responses that provide type safety and IDE support.

---

## Quick Navigation

<details>
<summary><strong>ListPage</strong></summary>

- [Overview](#listpage)
- [Generic Type](#listpage-generic)
- [Properties/Getters](#listpage-properties)
- [Example Usage](#listpage-example-usage)

</details>

<details>
<summary><strong>App</strong></summary>

- [Overview](#appdata)
- [Factory Methods](#appdata-factory-methods)
- [Properties/Getters](#appdata-properties)
- [Example Usage](#appdata-example-usage)
- [Store Configuration Structure](#appdata-store-configuration-structure)
- *Related Types:*
    - [StoreKitConfigData](#storekitconfigdata)

</details>

<details>
<summary><strong>Customer</strong></summary>

- [Overview](#customerdata)
- [Factory Methods](#customerdata-factory-methods)
- [Properties/Getters](#customerdata-properties)
- [Example Usage](#customerdata-example-usage)
- *Related Types:*
    - [AliasData](#aliasdata)
    - [AttributeData](#attributedata)
    - [VirtualCurrencyBalanceData](#virtualcurrencybalancedata)
    - [ActiveEntitlementData](#activeentitlementdata)

</details>

<details>
<summary><strong>Entitlement</strong></summary>

- [Overview](#entitlementdata)
- [Factory Methods](#entitlementdata-factory-methods)
- [Properties/Getters](#entitlementdata-properties)
- [Example Usage](#entitlementdata-example-usage)

</details>

<details>
<summary><strong>Invoice</strong></summary>

- [Overview](#invoicedata)
- [Factory Methods](#invoicedata-factory-methods)
- [Properties/Getters](#invoicedata-properties)
- [Example Usage](#invoicedata-example-usage)
- *Related Types:*
    - [Amount](#invoice-amount)
    - [LineItem](#invoice-lineitem)

</details>

<details>
<summary><strong>Offering</strong></summary>

- [Overview](#offeringdata)
- [Factory Methods](#offeringdata-factory-methods)
- [Properties/Getters](#offeringdata-properties)
- [Example Usage](#offeringdata-example-usage)

</details>

<details>
<summary><strong>Package</strong></summary>

- [Overview](#packagedata)
- [Factory Methods](#packagedata-factory-methods)
- [Properties/Getters](#packagedata-properties)
- [Example Usage](#packagedata-example-usage)

</details>

<details>
<summary><strong>Paywall</strong></summary>

- [Overview](#paywalldata)
- [Factory Methods](#paywalldata-factory-methods)
- [Properties/Getters](#paywalldata-properties)
- [Example Usage](#paywalldata-example-usage)

</details>

<details>
<summary><strong>Product</strong></summary>

- [Overview](#productdata)
- [Factory Methods](#productdata-factory-methods)
- [Properties/Getters](#productdata-properties)
- [Example Usage](#productdata-example-usage)

</details>

<details>
<summary><strong>Project</strong></summary>

- [Overview](#projectdata)
- [Factory Methods](#projectdata-factory-methods)
- [Properties/Getters](#projectdata-properties)
- [Example Usage](#projectdata-example-usage)

</details>

<details>
<summary><strong>Purchase</strong></summary>

- [Overview](#purchasedata)
- [Factory Methods](#purchasedata-factory-methods)
- [Properties/Getters](#purchasedata-properties)
- [Example Usage](#purchasedata-example-usage)

</details>

<details>
<summary><strong>Subscription</strong></summary>

- [Overview](#subscriptiondata)
- [Factory Methods](#subscriptiondata-factory-methods)
- [Properties/Getters](#subscriptiondata-properties)
- [Example Usage](#subscriptiondata-example-usage)
- *Related Types:*
    - [ManagementUrlData](#managementurldata)
    - [TransactionData](#transactiondata)

</details>

<a id="listpage"></a>
## ListPage

Represents a paginated collection of data objects with cursor-based pagination support.

<a id="listpage-generic"></a>
### Generic Type Support

ListPage uses PHP generics to provide type safety for collections:

```php
/**
 * @template T of object
 */
class ListPage<T> {
    // T can be any data object: AppData, CustomerData, ProductData, etc.
}
```

<a id="listpage-properties"></a>
### Properties/Getters

| Method | Return Type | Description |
|--------|-------------|-------------|
| `items()` | `array<int, T>` | Array of data objects in the current page. |
| `nextCursor()` | `?string` | Cursor for the next page, if available. |
| `url()` | `string` | API endpoint URL used for this request. |
| `raw()` | `Response` | Raw HTTP response from the API. |

<a id="listpage-example-usage"></a>
### Example Usage

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// ListPage<CustomerData>
$customers = RevenueCat::listCustomers();

// Access the items
foreach ($customers->items() as $customer) {
    echo $customer->getId();
    echo $customer->getLastSeenPlatform();
}

// Check for next page
if ($customers->nextCursor()) {
    // Use cursor for next request
    $nextPage = RevenueCat::listCustomers(limit: 20, startingAfter: $customers->nextCursor());
}

// Access raw response
$httpResponse = $customers->raw();
$statusCode = $httpResponse->status();

// Pagination loop example
$allCustomers = [];
$cursor = null;

do {
    $page = RevenueCat::listCustomers(limit: 50, startingAfter: $cursor);
    $allCustomers = array_merge($allCustomers, $page->items());
    $cursor = $page->nextCursor();
} while ($cursor !== null);
```

[↑ Back to Top](#quick-navigation)

---

<a id="appdata"></a>
## AppData

Represents an application in the RevenueCat system with its configuration and store settings.

<a id="appdata-factory-methods"></a>
### Factory Methods

| Method | Return Type | Description |
|--------|-------------|-------------|
| `fromArray()` | `AppData` | Create from array.. |
| `fromResponse()` | `AppData` | Create from HTTP response. |

<a id="appdata-properties"></a>
### Properties/Getters

| Method | Return Type | Description |
|--------|-------------|-------------|
| `getId()` | `string` | Unique identifier of the app. |
| `getResourceType()` | `string` | The resource type (object field). |
| `getName()` | `?string` | Human-readable name, if provided. |
| `getCreatedAtMs()` | `?int` | Timestamp in ms since epoch (UTC). |
| `getCreatedAtDate()` | `?\DateTimeImmutable` | Date object derived from created_at. |
| `getType()` | `?string` | App type, if present. |
| `getProjectId()` | `?string` | Associated project ID. |
| `getAmazon()` | `?array<string, mixed>` | Amazon store configuration. |
| `getAppStore()` | `?array<string, mixed>` | Apple App Store configuration. |
| `getMacAppStore()` | `?array<string, mixed>` | Mac App Store configuration. |
| `getPlayStore()` | `?array<string, mixed>` | Google Play Store configuration. |
| `getStripe()` | `?array<string, mixed>` | Stripe billing configuration. |
| `getRcBilling()` | `?array<string, mixed>` | RevenueCat billing configuration. |
| `getRoku()` | `?array<string, mixed>` | Roku store configuration. |
| `getPaddle()` | `?array<string, mixed>` | Paddle store configuration. |
| `getRaw()` | `array<string, mixed>` | Raw response payload. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

<a id="appdata-example-usage"></a>
### Example Usage

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$app = RevenueCat::getApp('app_123');

// Basic properties
echo $app->getId();           // 'app_123'
echo $app->getName();         // 'My Awesome App'
echo $app->getType();         // 'play_store'

// Store configurations
$playStore = $app->getPlayStore();
// Returns: ['package_name' => 'com.example.app', 'shared_secret' => '...']

$stripe = $app->getStripe();
// Returns: ['publishable_key' => '...', 'secret_key' => '...']

// Timestamps
$createdAt = $app->getCreatedAtDate();
// DateTimeImmutable object in UTC

// Raw data access
$rawData = $app->getRaw();
// Complete API response as received
```

<a id="appdata-store-configuration-structure"></a>
### Store Configuration Structure

Each store configuration array contains store-specific settings:

**Google Play Store:**
```php
[
    'package_name' => 'com.example.app',
    'service_account' => '...',
    'shared_secret' => '...'
]
```

**Apple App Store:**
```php
[
    'bundle_id' => 'com.example.app',
    'shared_secret' => '...'
]
```

**Stripe:**
```php
[
    'publishable_key' => 'pk_live_...',
    'secret_key' => 'sk_live_...'
]
```

[↑ Back to Top](#quick-navigation)

---

<a id="storekitconfigdata"></a>
### StoreKitConfigData

Represents Apple StoreKit configuration data for iOS/macOS apps in RevenueCat.

### Factory Methods

| Method | Return Type | Description |
|---|---|---|
| `fromArray()` | `StoreKitConfigData` | Create from array. |
| `fromResponse()` | `StoreKitConfigData` | Create from HTTP response. |

### Properties/Getters

| Method | Return Type | Description |
|---|---|---|
| `getResourceType()` | `string` | The resource type (always 'store_kit_config_file'). |
| `getContents()` | `array<string, mixed>` | The StoreKit configuration contents. |
| `getRaw()` | `array<string, mixed>` | Raw response payload. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

### Example Usage

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Get StoreKit config for an Apple app
$config = RevenueCat::getAppStoreKitConfig('app_123');

// Access the configuration data
$contents = $config->getContents(); // Array with StoreKit config
$resourceType = $config->getResourceType(); // "store_kit_config_file"
```

[↑ Back to Top](#quick-navigation)

---

<a id="customerdata"></a>
## CustomerData

Represents a customer in the RevenueCat system with their profile, activity history, and entitlements.

### Factory Methods

| Method | Return Type | Description |
|--------|-------------|-------------|
| `fromArray()` | `CustomerData` | Create from array.. |
| `fromResponse()` | `CustomerData` | Create from HTTP response. |

<a id="customerdata-properties"></a>
### Properties/Getters

| Method | Return Type | Description |
|--------|-------------|-------------|
| `getId()` | `string` | Unique identifier of the customer. |
| `getProjectId()` | `?string` | Associated project ID. |
| `getFirstSeenAtMs()` | `?int` | First seen timestamp in ms since epoch (UTC). |
| `getFirstSeenAtDate()` | `?\DateTimeImmutable` | First seen timestamp as DateTime object. |
| `getLastSeenAtMs()` | `?int` | Last seen timestamp in ms since epoch (UTC). |
| `getLastSeenAtDate()` | `?\DateTimeImmutable` | Last seen timestamp as DateTime object. |
| `getLastSeenAppVersion()` | `?string` | App version when last seen. |
| `getLastSeenCountry()` | `?string` | Country code when last seen. |
| `getLastSeenPlatform()` | `?string` | Platform when last seen (ios, android, etc.). |
| `getLastSeenPlatformVersion()` | `?string` | Platform version when last seen. |
| `getActiveEntitlements()` | `?array<string, mixed>` | Active entitlements data. |
| `getExperiment()` | `?array<string, mixed>` | Experiment data, if any. |
| `getAttributes()` | `?array<string, mixed>` | Custom attributes data. |
| `getRaw()` | `array<string, mixed>` | Raw response payload. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

<a id="customerdata-example-usage"></a>
### Example Usage

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$customer = RevenueCat::getCustomer('cus_123');

// Basic information
echo $customer->getId();                    // 'cus_123'
echo $customer->getLastSeenPlatform();      // 'ios'
echo $customer->getLastSeenCountry();       // 'US'

// Activity tracking
$firstSeen = $customer->getFirstSeenAtDate();
// DateTimeImmutable object

$lastSeen = $customer->getLastSeenAtDate();
// DateTimeImmutable object

// Entitlements and data
$entitlements = $customer->getActiveEntitlements();
// Array of active entitlements

$attributes = $customer->getAttributes();
// Custom attributes set for this customer
```

[↑ Back to Top](#quick-navigation)

---

<a id="aliasdata"></a>
### AliasData

Represents an alias/ID mapping for a customer in RevenueCat.

| Method | Return Type | Description |
|--------|-------------|-------------|
| `fromArray()` | `AliasData` | Create from array.. |
| `fromResponse()` | `AliasData` | Create from HTTP response. |
| `getId()` | `string` | The alias identifier. |
| `getResourceType()` | `string` | The resource type (always 'alias'). |
| `getCreatedAtMs()` | `?int` | Creation timestamp in ms since epoch. |
| `getCreatedAtDate()` | `?\DateTimeImmutable` | Creation timestamp as DateTime object. |
| `getRaw()` | `array<string, mixed>` | Raw response payload. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

[↑ Back to Top](#quick-navigation)

---

<a id="attributedata"></a>
### AttributeData

Represents a custom attribute set on a customer.

| Method | Return Type | Description |
|--------|-------------|-------------|
| `fromArray()` | `AttributeData` | Create from array.. |
| `fromResponse()` | `AttributeData` | Create from HTTP response. |
| `getResourceType()` | `string` | The resource type (always 'attribute'). |
| `getName()` | `string` | Attribute name/key. |
| `getValue()` | `?string` | Attribute value. |
| `getUpdatedAtMs()` | `?int` | Last update timestamp in ms since epoch. |
| `getUpdatedAtDate()` | `?\DateTimeImmutable` | Last update timestamp as DateTime object. |
| `getRaw()` | `array<string, mixed>` | Raw response payload. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

[↑ Back to Top](#quick-navigation)

---

<a id="virtualcurrencybalancedata"></a>
### VirtualCurrencyBalanceData

Represents a virtual currency balance for a customer.

| Method | Return Type | Description |
|--------|-------------|-------------|
| `fromArray()` | `VirtualCurrencyBalanceData` | Create from array.. |
| `fromResponse()` | `VirtualCurrencyBalanceData` | Create from HTTP response. |
| `getResourceType()` | `string` | The resource type. |
| `getCurrencyCode()` | `string` | Currency code (e.g., 'USD', 'EUR'). |
| `getBalance()` | `int` | Current balance amount. |
| `getRaw()` | `array<string, mixed>` | Raw response payload. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

[↑ Back to Top](#quick-navigation)

---

<a id="activeentitlementdata"></a>
### ActiveEntitlementData

Represents an active entitlement for a customer.

| Method | Return Type | Description |
|--------|-------------|-------------|
| `fromArray()` | `ActiveEntitlementData` | Create from array.. |
| `fromResponse()` | `ActiveEntitlementData` | Create from HTTP response. |
| `getEntitlementId()` | `string` | The entitlement identifier. |
| `getExpiresAtMs()` | `?int` | Expiration timestamp in ms since epoch. |
| `getExpiresAtDate()` | `?\DateTimeImmutable` | Expiration timestamp as DateTime object. |
| `getResourceType()` | `string` | The resource type. |
| `getRaw()` | `array<string, mixed>` | Raw response payload. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

[↑ Back to Top](#quick-navigation)

---

<a id="entitlementdata"></a>
## EntitlementData

Represents an entitlement in the RevenueCat system, which defines access levels or features that customers can have.

### Factory Methods

| Method | Return Type | Description |
|--------|-------------|-------------|
| `fromArray()` | `EntitlementData` | Create from array.. |
| `fromResponse()` | `EntitlementData` | Create from HTTP response. |

<a id="entitlementdata-properties"></a>
### Properties/Getters

| Method | Return Type | Description |
|--------|-------------|-------------|
| `getId()` | `string` | Unique identifier of the entitlement. |
| `getResourceType()` | `string` | The resource type (object field). |
| `getProjectId()` | `?string` | Associated project ID. |
| `getLookupKey()` | `?string` | Lookup key for the entitlement. |
| `getDisplayName()` | `?string` | Human-readable display name. |
| `getCreatedAtMs()` | `?int` | Creation timestamp in ms since epoch. |
| `getCreatedAtDate()` | `?\DateTimeImmutable` | Creation timestamp as DateTime object. |
| `getProducts()` | `?array<int, ProductData>` | Array of associated products. |
| `getRaw()` | `array<string, mixed>` | Raw response payload. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

<a id="entitlementdata-example-usage"></a>
### Example Usage

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$entitlement = RevenueCat::getEntitlement('ent_123');

// Basic information
echo $entitlement->getId();              // 'ent_123'
echo $entitlement->getLookupKey();       // 'premium_access'
echo $entitlement->getDisplayName();     // 'Premium Access'

// Associated products
$products = $entitlement->getProducts();
if ($products) {
    foreach ($products as $product) {
        echo $product->getId();          // Product ID
        echo $product->getStoreIdentifier(); // Store identifier
    }
}

// Creation date
$created = $entitlement->getCreatedAtDate();
// DateTimeImmutable object
```

[↑ Back to Top](#quick-navigation)

---

<a id="invoicedata"></a>
## InvoiceData

Represents a RevenueCat invoice, including total amount, line items, and timestamps.

<a id="invoicedata-factory-methods"></a>
### Factory Methods

| Method | Return Type | Description |
|--------|-------------|-------------|
| `fromArray()` | `InvoiceData` | Create from array.. |
| `fromResponse()` | `InvoiceData` | Create from HTTP response. |

<a id="invoicedata-properties"></a>
### Properties/Getters

| Method | Return Type | Description |
|--------|-------------|-------------|
| `getId()` | `string` | Unique identifier of the invoice. |
| `getResourceType()` | `string` | The resource type (object field). |
| `getTotalAmount()` | `\BoldlineStudios\RevenueCatApi\Data\Invoice\Amount` | Total amount for the invoice. |
| `getLineItems()` | `array<int, \BoldlineStudios\RevenueCatApi\Data\Invoice\LineItem>` | Line items included in the invoice. |
| `getIssuedAtMs()` | `?int` | Issued timestamp in ms since epoch (UTC). |
| `getIssuedAtDate()` | `?\DateTimeImmutable` | Issued timestamp as DateTime object. |
| `getPaidAtMs()` | `?int` | Paid timestamp in ms since epoch (UTC). |
| `getPaidAtDate()` | `?\DateTimeImmutable` | Paid timestamp as DateTime object. |
| `getInvoiceUrl()` | `?string` | URL to view/download the invoice, if available. |
| `getRaw()` | `array<string, mixed>` | Raw response payload. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

<a id="invoicedata-example-usage"></a>
### Example Usage

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// List a customer's invoices
$invoices = RevenueCat::listCustomerInvoices('cus_123');

foreach ($invoices->items() as $invoice) {
    $id = $invoice->getId();
    $total = $invoice->getTotalAmount();
    $issuedAt = $invoice->getIssuedAtDate();
    $url = $invoice->getInvoiceUrl();

    // Amount DTO
    // $total->getCurrency(); $total->getGross(); $total->getProceeds(); $total->getTax();

    // Line items
    foreach ($invoice->getLineItems() as $item) {
        // $item->getDescription(); $item->getQuantity(); $item->getAmount(); etc.
    }
}
```

[↑ Back to Top](#quick-navigation)

---

<a id="invoice-amount"></a>
### Amount (Invoice)

Represents an invoice amount with currency and financial components.

### Factory Methods

| Method | Return Type | Description |
|--------|-------------|-------------|
| `fromArray()` | `Amount` | Create from array.. |

### Properties/Getters

| Method | Return Type | Description |
|--------|-------------|-------------|
| `getResourceType()` | `string` | The resource type (e.g., 'MonetaryAmount'). |
| `getCurrency()` | `string` | ISO currency code (e.g., 'USD'). |
| `getGross()` | `float` | Gross amount. |
| `getCommission()` | `?float` | Commission amount, if present. |
| `getTax()` | `float` | Tax amount. |
| `getProceeds()` | `float` | Net proceeds amount. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

[↑ Back to Top](#quick-navigation)

---

<a id="invoice-lineitem"></a>
### LineItem (Invoice)

Represents a single invoice line item with product and unit amount details.

### Factory Methods

| Method | Return Type | Description |
|--------|-------------|-------------|
| `fromArray()` | `LineItem` | Create from array.. |

### Properties/Getters

| Method | Return Type | Description |
|--------|-------------|-------------|
| `getResourceType()` | `string` | The resource type. |
| `getProductIdentifier()` | `string` | Product identifier. |
| `getProductDisplayName()` | `?string` | Display name of the product. |
| `getProductDuration()` | `?string` | Product duration (if provided). |
| `getQuantity()` | `int` | Quantity for this line item. |
| `getUnitAmount()` | `Amount` | Unit amount as an Amount DTO. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

[↑ Back to Top](#quick-navigation)

---

<a id="offeringdata"></a>
## OfferingData

Represents a product offering in RevenueCat, which groups packages together for presentation to users.

### Factory Methods

| Method | Return Type | Description |
|--------|-------------|-------------|
| `fromArray()` | `OfferingData` | Create from array.. |
| `fromResponse()` | `OfferingData` | Create from HTTP response. |

<a id="offeringdata-properties"></a>
### Properties/Getters

| Method | Return Type | Description |
|--------|-------------|-------------|
| `getId()` | `string` | Unique identifier of the offering. |
| `getResourceType()` | `string` | The resource type (object field). |
| `getLookupKey()` | `?string` | Lookup key for the offering. |
| `getDisplayName()` | `?string` | Human-readable display name. |
| `isCurrent()` | `?bool` | Whether this is the current offering. |
| `getCreatedAtMs()` | `?int` | Creation timestamp in ms since epoch. |
| `getCreatedAtDate()` | `?\DateTimeImmutable` | Creation timestamp as DateTime object. |
| `getProjectId()` | `?string` | Associated project ID. |
| `getMetadata()` | `?array<string, mixed>` | Custom metadata for the offering. |
| `getPackages()` | `?array<string, mixed>` | Associated packages data. |
| `getRaw()` | `array<string, mixed>` | Raw response payload. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

<a id="offeringdata-example-usage"></a>
### Example Usage

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$offering = RevenueCat::getOffering('offering_123');

// Basic information
echo $offering->getId();              // 'offering_123'
echo $offering->getLookupKey();       // 'premium_monthly'
echo $offering->getDisplayName();     // 'Premium Monthly'
echo $offering->isCurrent();          // true/false

// Metadata and packages
$metadata = $offering->getMetadata();
// Custom key-value pairs

$packages = $offering->getPackages();
// Package data as returned by API
```

[↑ Back to Top](#quick-navigation)

---

<a id="packagedata"></a>
## PackageData

Represents a package within an offering in RevenueCat, containing pricing and product information.

### Factory Methods

| Method | Return Type | Description |
|--------|-------------|-------------|
| `fromArray()` | `PackageData` | Create from array.. |
| `fromResponse()` | `PackageData` | Create from HTTP response. |

<a id="packagedata-properties"></a>
### Properties/Getters

| Method | Return Type | Description |
|--------|-------------|-------------|
| `getId()` | `string` | Unique identifier of the package. |
| `getResourceType()` | `string` | The resource type (object field). |
| `getLookupKey()` | `?string` | Lookup key for the package. |
| `getDisplayName()` | `?string` | Human-readable display name. |
| `getPosition()` | `?int` | Display position/order. |
| `getCreatedAtMs()` | `?int` | Creation timestamp in ms since epoch. |
| `getCreatedAtDate()` | `?\DateTimeImmutable` | Creation timestamp as DateTime object. |
| `getProducts()` | `?array<string, mixed>` | Associated products data. |
| `getRaw()` | `array<string, mixed>` | Raw response payload. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

<a id="packagedata-example-usage"></a>
### Example Usage

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$package = RevenueCat::getPackage('package_123');

// Basic information
echo $package->getId();              // 'package_123'
echo $package->getLookupKey();       // 'monthly'
echo $package->getDisplayName();     // 'Monthly Subscription'
echo $package->getPosition();        // 1, 2, 3...

// Associated products
$products = $package->getProducts();
// Product data as returned by API

// Creation date
$created = $package->getCreatedAtDate();
// DateTimeImmutable object
```

[↑ Back to Top](#quick-navigation)

---

<a id="paywalldata"></a>
## PaywallData

Represents a paywall configuration in RevenueCat, used for presenting subscription options to users.

### Factory Methods

| Method | Return Type | Description |
|--------|-------------|-------------|
| `fromArray()` | `PaywallData` | Create from array.. |
| `fromResponse()` | `PaywallData` | Create from HTTP response. |

<a id="paywalldata-properties"></a>
### Properties/Getters

| Method | Return Type | Description |
|--------|-------------|-------------|
| `getId()` | `string` | Unique identifier of the paywall. |
| `getResourceType()` | `string` | The resource type (object field). |
| `getName()` | `?string` | Human-readable name, if provided. |
| `getOfferingId()` | `string` | Associated offering identifier. |
| `getCreatedAtMs()` | `?int` | Creation timestamp in ms since epoch. |
| `getCreatedAtDate()` | `?\DateTimeImmutable` | Creation timestamp as DateTime object. |
| `getPublishedAtMs()` | `?int` | Publication timestamp in ms since epoch. |
| `getPublishedAtDate()` | `?\DateTimeImmutable` | Publication timestamp as DateTime object. |
| `getRaw()` | `array<string, mixed>` | Raw response payload. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

<a id="paywalldata-example-usage"></a>
### Example Usage

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$paywall = RevenueCat::createPaywall('offering_123');

// Basic information
echo $paywall->getId();              // 'paywall_456'
echo $paywall->getName();            // 'Premium Paywall'
echo $paywall->getOfferingId();      // 'offering_123'

// Publication status
$publishedAt = $paywall->getPublishedAtDate();
if ($publishedAt) {
    echo "Published: " . $publishedAt->format('Y-m-d');
} else {
    echo "Not yet published";
}

// Creation date
$created = $paywall->getCreatedAtDate();
// DateTimeImmutable object
```

[↑ Back to Top](#quick-navigation)

---

<a id="productdata"></a>
## ProductData

Represents a product configuration in RevenueCat, containing pricing information and store-specific details.

### Factory Methods

| Method | Return Type | Description |
|--------|-------------|-------------|
| `fromArray()` | `ProductData` | Create from array.. |
| `fromResponse()` | `ProductData` | Create from HTTP response. |

<a id="productdata-properties"></a>
### Properties/Getters

| Method | Return Type | Description |
|--------|-------------|-------------|
| `getId()` | `string` | Unique identifier of the product. |
| `getResourceType()` | `string` | The resource type (object field). |
| `getStoreIdentifier()` | `?string` | Store-specific product identifier. |
| `getType()` | `?string` | Product type (subscription, one_time). |
| `getSubscription()` | `?array<string, mixed>` | Subscription pricing configuration. |
| `getOneTime()` | `?array<string, mixed>` | One-time purchase pricing configuration. |
| `getCreatedAtMs()` | `?int` | Creation timestamp in ms since epoch. |
| `getCreatedAtDate()` | `?\DateTimeImmutable` | Creation timestamp as DateTime object. |
| `getAppId()` | `?string` | Associated app identifier. |
| `getApp()` | `?AppData` | Associated app as AppData DTO. |
| `getDisplayName()` | `?string` | Human-readable product name. |
| `getRaw()` | `array<string, mixed>` | Raw response payload. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

<a id="productdata-example-usage"></a>
### Example Usage

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$product = RevenueCat::getProduct('prod_123');

// Basic information
echo $product->getId();              // 'prod_123'
echo $product->getStoreIdentifier(); // 'com.example.app.premium'
echo $product->getType();            // 'subscription'
echo $product->getDisplayName();     // 'Premium Subscription'

// Pricing information
$subscription = $product->getSubscription();
// Returns: ['price' => 9.99, 'currency' => 'USD', 'period' => 'month']

$oneTime = $product->getOneTime();
// Returns: ['price' => 19.99, 'currency' => 'USD'] (for one-time purchases)

// Associated app
$app = $product->getApp();
if ($app) {
    echo $app->getName(); // App name
}
```

[↑ Back to Top](#quick-navigation)

---

<a id="projectdata"></a>
## ProjectData

Represents a RevenueCat project, which is the top-level organizational unit.

### Factory Methods

| Method | Return Type | Description |
|--------|-------------|-------------|
| `fromArray()` | `ProjectData` | Create from array.. |
| `fromResponse()` | `ProjectData` | Create from HTTP response. |

<a id="projectdata-properties"></a>
### Properties/Getters

| Method | Return Type | Description |
|--------|-------------|-------------|
| `getId()` | `string` | Unique identifier of the project. |
| `getResourceType()` | `string` | The resource type (object field). |
| `getName()` | `?string` | Human-readable project name. |
| `getCreatedAtMs()` | `?int` | Creation timestamp in ms since epoch. |
| `getCreatedAtDate()` | `?\DateTimeImmutable` | Creation timestamp as DateTime object. |
| `getRaw()` | `array<string, mixed>` | Raw response payload. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

<a id="projectdata-example-usage"></a>
### Example Usage

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// List all projects
$projects = RevenueCat::listProjects();

foreach ($projects->items() as $project) {
    echo $project->getId();     // Project ID
    echo $project->getName();   // Project name

    // Creation date
    $created = $project->getCreatedAtDate();
    if ($created) {
        echo $created->format('Y-m-d');
    }
}
```

[↑ Back to Top](#quick-navigation)

---

<a id="purchasedata"></a>
## PurchaseData

Represents a purchase/transaction in RevenueCat, containing customer and product information.

### Factory Methods

| Method | Return Type | Description |
|--------|-------------|-------------|
| `fromArray()` | `PurchaseData` | Create from array.. |
| `fromResponse()` | `PurchaseData` | Create from HTTP response. |

<a id="purchasedata-properties"></a>
### Properties/Getters

| Method | Return Type | Description |
|--------|-------------|-------------|
| `getId()` | `string` | Unique identifier of the purchase. |
| `getResourceType()` | `string` | The resource type (object field). |
| `getCustomerId()` | `?string` | Associated customer identifier. |
| `getOriginalCustomerId()` | `?string` | Original customer identifier. |
| `getProductId()` | `?string` | Associated product identifier. |
| `getPurchasedAtMs()` | `?int` | Purchase timestamp in ms since epoch. |
| `getPurchasedAtDate()` | `?\DateTimeImmutable` | Purchase timestamp as DateTime object. |
| `getRevenueInUsd()` | `?array<string, mixed>` | Revenue information in USD. |
| `getQuantity()` | `?int` | Quantity purchased. |
| `getStatus()` | `?string` | Purchase status. |
| `getPresentedOfferingId()` | `?string` | Offering that was presented. |
| `getEntitlements()` | `?array<EntitlementData>` | Associated entitlements. |
| `getEnvironment()` | `?string` | Environment (sandbox/production). |
| `getStore()` | `?string` | App store (app_store, play_store, etc.). |
| `getStorePurchaseIdentifier()` | `?string` | Store-specific purchase identifier. |
| `getOwnership()` | `?string` | Ownership type. |
| `getCountry()` | `?string` | Purchase country. |
| `getRaw()` | `array<string, mixed>` | Raw response payload. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

<a id="purchasedata-example-usage"></a>
### Example Usage

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// List customer purchases
$purchases = RevenueCat::listCustomerPurchases('cus_123');

foreach ($purchases->items() as $purchase) {
    echo $purchase->getId();                   // Purchase ID
    echo $purchase->getProductId();            // Product purchased
    echo $purchase->getStore();                // 'app_store', 'play_store'
    echo $purchase->getStatus();               // Purchase status

    // Purchase date
    $purchasedAt = $purchase->getPurchasedAtDate();
    if ($purchasedAt) {
        echo $purchasedAt->format('Y-m-d H:i:s');
    }

    // Revenue information
    $revenue = $purchase->getRevenueInUsd();
    // Returns: ['gross' => 9.99, 'tax' => 0.8, 'proceeds' => 9.19]

    // Entitlements granted
    $entitlements = $purchase->getEntitlements();
    foreach ($entitlements ?? [] as $entitlement) {
        echo $entitlement->getId(); // Entitlement ID
    }
}

// Search by store purchase identifier
$specificPurchase = RevenueCat::searchPurchasesByIdentifier('1000000123456789');
```

[↑ Back to Top](#quick-navigation)

---

<a id="subscriptiondata"></a>
## SubscriptionData

Represents a subscription in RevenueCat, containing billing cycle information, status, and associated entitlements.

### Factory Methods

| Method | Return Type | Description |
|--------|-------------|-------------|
| `fromArray()` | `SubscriptionData` | Create from array.. |
| `fromResponse()` | `SubscriptionData` | Create from HTTP response. |

<a id="subscriptiondata-properties"></a>
### Properties/Getters

| Method | Return Type | Description |
|--------|-------------|-------------|
| `getId()` | `string` | Unique identifier of the subscription. |
| `getResourceType()` | `string` | The resource type (object field). |
| `getCustomerId()` | `?string` | Associated customer identifier. |
| `getOriginalCustomerId()` | `?string` | Original customer identifier. |
| `getProductId()` | `?string` | Associated product identifier. |
| `getStartsAtMs()` | `?int` | Subscription start timestamp in ms since epoch. |
| `getStartsAtDate()` | `?\DateTimeImmutable` | Subscription start timestamp as DateTime object. |
| `getCurrentPeriodStartsAtMs()` | `?int` | Current billing period start timestamp in ms. |
| `getCurrentPeriodStartsAtDate()` | `?\DateTimeImmutable` | Current billing period start as DateTime object. |
| `getCurrentPeriodEndsAtMs()` | `?int` | Current billing period end timestamp in ms. |
| `getCurrentPeriodEndsAtDate()` | `?\DateTimeImmutable` | Current billing period end as DateTime object. |
| `getGivesAccess()` | `?bool` | Whether the subscription currently gives access. |
| `getPendingPayment()` | `?bool` | Whether there's a pending payment. |
| `getAutoRenewalStatus()` | `?string` | Auto-renewal status. |
| `getStatus()` | `?string` | Subscription status (active, expired, etc.). |
| `getTotalRevenueInUsd()` | `?array<string, mixed>` | Total revenue information in USD. |
| `getPresentedOfferingId()` | `?string` | Offering that was presented at purchase. |
| `getEntitlements()` | `?array<string, mixed>` | Associated entitlements data. |
| `getEnvironment()` | `?string` | Environment (sandbox/production). |
| `getStore()` | `?string` | App store (app_store, play_store, etc.). |
| `getStoreSubscriptionIdentifier()` | `?string` | Store-specific subscription identifier. |
| `getOwnership()` | `?string` | Ownership type. |
| `getPendingChangesProduct()` | `?ProductData` | Pending product change information. |
| `getCountry()` | `?string` | Subscription country. |
| `getManagementUrl()` | `?string` | URL for managing the subscription. |
| `getRaw()` | `array<string, mixed>` | Raw response payload. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

<a id="subscriptiondata-example-usage"></a>
### Example Usage

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Get subscription details
$subscription = RevenueCat::getSubscription('sub_123');

// Basic information
echo $subscription->getId();              // 'sub_123'
echo $subscription->getStatus();          // 'active', 'expired', etc.
echo $subscription->getStore();           // 'app_store', 'play_store'

// Billing cycle information
$periodStart = $subscription->getCurrentPeriodStartsAtDate();
$periodEnd = $subscription->getCurrentPeriodEndsAtDate();

// Auto-renewal status
echo $subscription->getAutoRenewalStatus(); // 'will_renew', 'will_not_renew'

// Revenue tracking
$revenue = $subscription->getTotalRevenueInUsd();
// Returns: ['gross' => 99.99, 'tax' => 8.0, 'proceeds' => 91.99]

// Store-specific information
echo $subscription->getStoreSubscriptionIdentifier(); // Store's subscription ID

// Management
if ($subscription->getManagementUrl()) {
    echo $subscription->getManagementUrl(); // Customer portal URL
}

// Transaction history
$transactions = RevenueCat::listSubscriptionTransactions('sub_123');
foreach ($transactions->items() as $transaction) {
    echo $transaction->getId();
    echo $transaction->getPurchasedAtDate()->format('Y-m-d');
}
```

[↑ Back to Top](#quick-navigation)

---

<a id="managementurldata"></a>
### ManagementUrlData

Represents a management URL for customer subscription portals in RevenueCat.

### Factory Methods

| Method | Return Type | Description |
|---|---|---|
| `fromArray()` | `ManagementUrlData` | Create from array. |
| `fromResponse()` | `ManagementUrlData` | Create from HTTP response. |

### Properties/Getters

| Method | Return Type | Description |
|---|---|---|
| `getManagementUrl()` | `string` | Secure, single-use URL for customer subscription management. |
| `getResourceType()` | `string` | The resource type (object field). |
| `getRaw()` | `array<string, mixed>` | Raw response payload. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

### Example Usage

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Get management URL for customer portal
$managementUrl = RevenueCat::getSubscriptionCustomerPortalUrl('sub_123');

// Access the secure URL
$portalUrl = $managementUrl->getManagementUrl();

// Provide this URL to customers for managing their subscription
echo "Customer Portal: {$portalUrl}";
```

[↑ Back to Top](#quick-navigation)

---

<a id="transactiondata"></a>
### TransactionData (Subscription)

Represents a subscription transaction in RevenueCat, containing purchase information for individual billing cycles.

### Factory Methods

| Method | Return Type | Description |
|--------|-------------|-------------|
| `fromArray()` | `TransactionData` | Create from array.. |
| `fromResponse()` | `TransactionData` | Create from HTTP response. |

### Properties/Getters

| Method | Return Type | Description |
|--------|-------------|-------------|
| `getId()` | `string` | Unique identifier of the transaction. |
| `getResourceType()` | `string` | The resource type (object field). |
| `getPurchasedAtMs()` | `?int` | Transaction timestamp in ms since epoch. |
| `getPurchasedAtDate()` | `?\DateTimeImmutable` | Transaction timestamp as DateTime object. |
| `getRaw()` | `array<string, mixed>` | Raw response payload. |
| `toArray()` | `array<string, mixed>` | Array representation of the object. |

[↑ Back to Top](#quick-navigation)

---

[Back to Quick Navigation](#quick-navigation)
