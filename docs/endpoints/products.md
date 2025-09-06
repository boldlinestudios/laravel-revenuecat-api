# Products

> For the official RevenueCat API reference, see [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2).

Manage RevenueCat products, which represent the actual store products (subscriptions and one-time purchases) that customers can buy.

**Related Types:** [`ProductData`](../../DATA.md#productdata), [`ListPage`](../../DATA.md#listpage)

---

## Methods

<details>
<summary><strong>Endpoint Methods</strong></summary>

**RevenueCat::products()**

---

| Action | Signature | Returns |
|---|---|---|
| Get | `get(string $productId)` | `ProductData` |
| List | `all(int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<ProductData>` |
| Create | `create(string $storeIdentifier, string $appId, string $type, ?string $displayName)` | `ProductData` |
| Delete | `delete(string $productId)` | `bool` |

</details>

<details>
<summary><strong>Convenience Methods</strong></summary>

| Action | Signature | Returns |
|---|---|---|
| Get | `getProduct(string $productId)` | `ProductData` |
| List | `listProducts(int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<ProductData>` |
| Create | `createProduct(string $storeIdentifier, string $appId, string $type, ?string $displayName)` | `ProductData` |
| Delete | `deleteProduct(string $productId)` | `bool` |

</details>

---

## Parameters

<details>
<summary><strong>Create Parameters</strong></summary>

| Name | Type | Required | Notes |
|---|---|:---:|---|
| `storeIdentifier` | `string` | ✓ | Store-specific product identifier (e.g., App Store product ID, Play Store SKU) |
| `appId` | `string` | ✓ | The app this product belongs to |
| `type` | `string` | ✓ | Product type: `subscription`, `one_time`, `consumable`, `non_consumable`, `non_renewing_subscription` |
| `displayName` | `?string` | Optional | Human-readable name for the product |

</details>

---

## Examples

<details open>
<summary><strong>Get</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$product = RevenueCat::products()->get('prod_123');

// Convenience-style
$product = RevenueCat::getProduct('prod_123');
```

</details>

<details open>
<summary><strong>List</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$page = RevenueCat::products()->all(limit: 20);

foreach ($page->items() as $product) {
    // $product is ProductData
}

// Convenience-style
$page = RevenueCat::listProducts(limit: 20);
```

</details>

<details open>
<summary><strong>Create</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Create a subscription product
$product = RevenueCat::products()->create(
    storeIdentifier: 'com.example.premium_monthly',
    appId: 'app_123',
    type: 'subscription',
    displayName: 'Premium Monthly'
);

// Create a one-time purchase product
$product = RevenueCat::products()->create(
    storeIdentifier: 'com.example.consumable',
    appId: 'app_123',
    type: 'one_time'
);

// Convenience-style
$product = RevenueCat::createProduct(
    'com.example.premium_monthly',
    'app_123',
    'subscription',
    'Premium Monthly'
);
```

</details>

<details open>
<summary><strong>Delete</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$deleted = RevenueCat::products()->delete('prod_123'); // bool

// Convenience-style
$deleted = RevenueCat::deleteProduct('prod_123');
```

</details>

---

## See also
- [`DATA.md`](../../DATA.md)
- Official docs: [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2)
