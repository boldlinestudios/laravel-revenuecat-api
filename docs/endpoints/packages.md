# Packages

> For the official RevenueCat API reference, see [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2).

**Related Types:** [`PackageData`](../../DATA.md#packagedata), [`ProductData`](../../DATA.md#productdata), [`ListPage`](../../DATA.md#listpage)

---

## Methods

<details>
<summary><strong>Endpoint Methods</strong></summary>

**RevenueCat::packages()**

---

| Action | Signature | Returns |
|---|---|---|
| Get | `get(string $packageId)` | `PackageData` |
| List (in Offering) | `listOfPackagesInOffering(string $offeringId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<PackageData>` |
| Create | `create(string $offeringId, string $lookupKey, string $displayName, ?int $position)` | `PackageData` |
| Update | `update(string $packageId, ?string $displayName, ?int $position)` | `PackageData` |
| Delete | `delete(string $packageId)` | `bool` |
| List Products | `listOfProducts(string $packageId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<ProductData>` |
| Attach Products | `attachProducts(string $packageId, array $productAssociationList)` | `PackageData` |
| Detach Products | `detachProducts(string $packageId, array $productIds)` | `PackageData` |

</details>

<details>
<summary><strong>Convenience Methods</strong></summary>

| Action | Signature | Returns |
|---|---|---|
| Get | `getPackage(string $packageId)` | `PackageData` |
| List (in Offering) | `listofPackagesinOffering(string $offeringId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<PackageData>` |
| Create | `createPackage(string $offeringId, string $lookupKey, string $displayName, ?int $position)` | `PackageData` |
| Update | `updatePackage(string $packageId, ?string $displayName, ?int $position)` | `PackageData` |
| Delete | `deletePackage(string $packageId)` | `bool` |
| List Products | `listPackageProducts(string $packageId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<ProductData>` |
| Attach Products | `attachPackageProducts(string $packageId, array $productAssociationList)` | `PackageData` |
| Detach Products | `detachPackageProducts(string $packageId, array $productIds)` | `PackageData` |

</details>

---

## Parameters

<details>
<summary><strong>Create Parameters</strong></summary>

| Name | Type | Required | Notes |
|---|---|:---:|---|
| `offeringId` | `string` | ✓ | The offering this package belongs to |
| `lookupKey` | `string` | ✓ | Unique identifier for the package |
| `displayName` | `string` | ✓ | Human-readable name for the package |
| `position` | `?int` | Optional | Display position/order of the package |

</details>

<details>
<summary><strong>Update Parameters</strong></summary>

| Name | Type | Required | Notes |
|---|---|:---:|---|
| `packageId` | `string` | ✓ | Package identifier |
| `displayName` | `?string` | Optional | Updated human-readable name |
| `position` | `?int` | Optional | Updated display position/order |

> **Note:** Null values will be ignored and not updated.

</details>

<details>
<summary><strong>Product Management Parameters</strong></summary>

| Name | Type | Required | Notes |
|---|---|:---:|---|
| `packageId` | `string` | ✓ | Package identifier |
| `productAssociationList` | `array<array{product_id: string, eligibility_criteria: string}>` | ✓ | List of products with eligibility criteria |
| `productIds` | `array<string>` | ✓ | Array of product identifiers to detach |

</details>

---

## Examples

<details open>
<summary><strong>Get</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$package = RevenueCat::packages()->get('pkg_123');

// Convenience-style
$package = RevenueCat::getPackage('pkg_123');
```

</details>

<details open>
<summary><strong>List</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

$offeringId = 'off_123'; // Replace with actual offering ID
$page = RevenueCat::packages()->listOfPackagesInOffering($offeringId, limit: 20, startingAfter: 'pkg_abc');

foreach ($page->items() as $package) {
    // $package is PackageData
}

// Convenience-style
$page = RevenueCat::listofPackagesinOffering($offeringId, limit: 20, startingAfter: 'pkg_abc');
```

</details>

<details open>
<summary><strong>Create</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

$offeringId = 'off_123'; // Replace with actual offering ID

// Create with basic information
$package = RevenueCat::packages()->create($offeringId, lookupKey: 'monthly_plan', displayName: 'Monthly Plan');

// Create with position
$package = RevenueCat::packages()->create($offeringId, lookupKey: 'yearly_plan', displayName: 'Yearly Plan', position: 1);

// Convenience-style
$package = RevenueCat::createPackage($offeringId, lookupKey: 'monthly_plan', displayName: 'Monthly Plan', position: 2);
```

</details>

<details open>
<summary><strong>Update</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

// Update display name
$package = RevenueCat::packages()->update('pkg_123', displayName: 'Updated Package Name');

// Update position
$package = RevenueCat::packages()->update('pkg_123', position: 3);

// Update both
$package = RevenueCat::packages()->update('pkg_123', displayName: 'New Name', position: 1);

// Convenience-style
$package = RevenueCat::updatePackage('pkg_123', displayName: 'New Name', position: 1);
```

</details>

<details open>
<summary><strong>Delete</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$deleted = RevenueCat::packages()->delete('pkg_123'); // bool

// Convenience-style
$deleted = RevenueCat::deletePackage('pkg_123');
```

</details>

<details open>
<summary><strong>List Products</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$products = RevenueCat::packages()->listOfProducts('pkg_123', limit: 50);

// Convenience-style
$products = RevenueCat::listPackageProducts('pkg_123', limit: 50);
```

</details>

<details open>
<summary><strong>Attach Products</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

$productAssociations = [
    [
        'product_id' => 'prod_123',
        'eligibility_criteria' => 'all'
    ],
    [
        'product_id' => 'prod_456',
        'eligibility_criteria' => 'google_sdk_lt_6'
    ]
];

// Endpoint-style
$package = RevenueCat::packages()->attachProducts('pkg_123', $productAssociations);

// Convenience-style
$package = RevenueCat::attachPackageProducts('pkg_123', $productAssociations);
```

</details>

<details open>
<summary><strong>Detach Products</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

$productIds = ['prod_123', 'prod_456'];

// Endpoint-style
$package = RevenueCat::packages()->detachProducts('pkg_123', $productIds);

// Convenience-style
$package = RevenueCat::detachPackageProducts('pkg_123', $productIds);
```

</details>

---

## See also
- [`DATA.md`](../../DATA.md)
- Official docs: [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2)
