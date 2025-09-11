# Entitlements

> For the official RevenueCat API reference, see [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2).

Manage RevenueCat entitlements and their associated products.

**Related Types:** [`EntitlementData`](../../DATA.md#entitlementdata), [`ProductData`](../../DATA.md#productdata), [`ListPage`](../../DATA.md#listpage)

---

## Methods

<details>
<summary><strong>Endpoint Methods</strong></summary>

<br>

**RevenueCat::entitlements()**

---

| Action | Signature | Returns |
|---|---|---|
| Get | `get(string $entitlementId)` | `EntitlementData` |
| List | `all(int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<EntitlementData>` |
| Create | `create(string $lookupKey, string $displayName)` | `EntitlementData` |
| Update | `update(string $entitlementId, string $displayName)` | `EntitlementData` |
| Delete | `delete(string $entitlementId)` | `bool` |
| List Products | `listOfProducts(string $entitlementId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<ProductData>` |
| Attach Products | `attachProducts(string $entitlementId, array $productIds)` | `EntitlementData` |
| Detach Products | `detachProducts(string $entitlementId, array $productIds)` | `EntitlementData` |

</details>

<details>
<summary><strong>Convenience Methods</strong></summary>

<br>

| Action | Signature | Returns |
|---|---|---|
| Get | `getEntitlement(string $entitlementId)` | `EntitlementData` |
| List | `listEntitlements(int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<EntitlementData>` |
| Create | `createEntitlement(string $lookupKey, string $displayName)` | `EntitlementData` |
| Update | `updateEntitlement(string $entitlementId, string $displayName)` | `EntitlementData` |
| Delete | `deleteEntitlement(string $entitlementId)` | `bool` |
| List Products | `listEntitlementProducts(string $entitlementId)` | `ListPage<ProductData>` |
| Attach Products | `attachEntitlementProducts(string $entitlementId, array $productIds)` | `EntitlementData` |
| Detach Products | `detachEntitlementProducts(string $entitlementId, array $productIds)` | `EntitlementData` |

</details>

---

## Parameters

<details>
<summary><strong>Create Parameters</strong></summary>

| Name | Type | Required | Notes |
|---|---|:---:|---|
| `lookupKey` | `string` | ✓ | Unique identifier for the entitlement |
| `displayName` | `string` | ✓ | Human-readable name for the entitlement |

</details>

<details>
<summary><strong>Update Parameters</strong></summary>

| Name | Type | Required | Notes |
|---|---|:---:|---|
| `entitlementId` | `string` | ✓ | Entitlement identifier |
| `displayName` | `string` | ✓ | Updated human-readable name |

</details>

<details>
<summary><strong>Product Management Parameters</strong></summary>

| Name | Type | Required | Notes |
|---|---|:---:|---|
| `entitlementId` | `string` | ✓ | Entitlement identifier |
| `productIds` | `array<string>` | ✓ | Array of product identifiers to attach/detach |

</details>

---

## Examples

<details open>
<summary><strong>Get</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$entitlement = RevenueCat::entitlements()->get('ent_123');

// Convenience-style
$entitlement = RevenueCat::getEntitlement('ent_123');
```

</details>

<details open>
<summary><strong>List</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

$page = RevenueCat::entitlements()->all(limit: 20);

foreach ($page->items() as $entitlement) {
    // $entitlement is EntitlementData
}

// Convenience-style
$page = RevenueCat::listEntitlements(limit: 20);
```

</details>

<details open>
<summary><strong>Create</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$entitlement = RevenueCat::entitlements()->create(lookupKey: 'premium_access', displayName: 'Premium Access');

// Convenience-style
$entitlement = RevenueCat::createEntitlement(lookupKey: 'premium_access', displayName: 'Premium Access');
```

</details>

<details open>
<summary><strong>Update</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$entitlement = RevenueCat::entitlements()->update('ent_123', 'Updated Premium Access');

// Convenience-style
$entitlement = RevenueCat::updateEntitlement('ent_123', 'Updated Premium Access');
```

</details>

<details open>
<summary><strong>Delete</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$deleted = RevenueCat::entitlements()->delete('ent_123'); // bool

// Convenience-style
$deleted = RevenueCat::deleteEntitlement('ent_123');
```

</details>

<details open>
<summary><strong>List Products</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$products = RevenueCat::entitlements()->listOfProducts('ent_123', limit: 50);

// Convenience-style
$products = RevenueCat::listEntitlementProducts('ent_123');
```

</details>

<details open>
<summary><strong>Attach Products</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

$productIds = ['prod_123', 'prod_456'];

// Endpoint-style
$entitlement = RevenueCat::entitlements()->attachProducts('ent_123', $productIds);

// Convenience-style
$entitlement = RevenueCat::attachEntitlementProducts('ent_123', $productIds);
```

</details>

<details open>
<summary><strong>Detach Products</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

$productIds = ['prod_123'];

// Endpoint-style
$entitlement = RevenueCat::entitlements()->detachProducts('ent_123', $productIds);

// Convenience-style
$entitlement = RevenueCat::detachEntitlementProducts('ent_123', $productIds);
```

</details>

---

## See also
- [`DATA.md`](../../DATA.md)
- Official docs: [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2)
