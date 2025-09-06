# Offerings

> For the official RevenueCat API reference, see [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2).

Manage RevenueCat offerings, which are collections of packages that can be presented to customers.

**Related Types:** [`OfferingData`](../../DATA.md#offeringdata), [`ListPage`](../../DATA.md#listpage)

---

## Methods

<details>
<summary><strong>Endpoint Methods</strong></summary>

**RevenueCat::offerings()**

---

| Action | Signature | Returns |
|---|---|---|
| Get | `get(string $offeringId)` | `OfferingData` |
| List | `all(int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<OfferingData>` |
| Create | `create(string $lookupKey, string $displayName, ?array $metadata)` | `OfferingData` |
| Update | `update(string $offeringId, ?string $displayName, ?bool $isCurrent, ?array $metadata)` | `OfferingData` |
| Delete | `delete(string $offeringId)` | `bool` |

</details>

<details>
<summary><strong>Convenience Methods</strong></summary>

| Action | Signature | Returns |
|---|---|---|
| Get | `getOffering(string $offeringId)` | `OfferingData` |
| List | `listOfferings(int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<OfferingData>` |
| Create | `createOffering(string $lookupKey, string $displayName, ?array $metadata)` | `OfferingData` |
| Update | `updateOffering(string $offeringId, ?string $displayName, ?bool $isCurrent, ?array $metadata)` | `OfferingData` |
| Delete | `deleteOffering(string $offeringId)` | `bool` |

</details>

---

## Parameters

<details>
<summary><strong>Create Parameters</strong></summary>

| Name | Type | Required | Notes |
|---|---|:---:|---|
| `lookupKey` | `string` | ✓ | Unique identifier for the offering |
| `displayName` | `string` | ✓ | Human-readable name for the offering |
| `metadata` | `?array<string, mixed>` | Optional | Additional metadata (e.g., color, call_to_action) |

</details>

<details>
<summary><strong>Update Parameters</strong></summary>

| Name | Type | Required | Notes |
|---|---|:---:|---|
| `offeringId` | `string` | ✓ | Offering identifier |
| `displayName` | `?string` | Optional | Updated human-readable name |
| `isCurrent` | `?bool` | Optional | Whether this is the current offering |
| `metadata` | `?array<string, mixed>` | Optional | Updated metadata |

> **Note:** Null values will be ignored and not updated.

</details>

---

## Examples

<details open>
<summary><strong>Get</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$offering = RevenueCat::offerings()->get('off_123');

// Convenience-style
$offering = RevenueCat::getOffering('off_123');
```

</details>

<details open>
<summary><strong>List</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$page = RevenueCat::offerings()->all(limit: 20);

foreach ($page->items() as $offering) {
    // $offering is OfferingData
}

// Convenience-style
$page = RevenueCat::listOfferings(limit: 20);
```

</details>

<details open>
<summary><strong>Create</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Create with basic information
$offering = RevenueCat::offerings()->create(lookupKey: 'premium_plan', displayName: 'Premium Plan');

// Create with metadata
$metadata = [
    'color' => 'blue',
    'call_to_action' => 'Get Premium Now'
];

$offering = RevenueCat::offerings()->create(lookupKey: 'premium_plan', displayName: 'Premium Plan', metadata: $metadata);

// Convenience-style
$offering = RevenueCat::createOffering(lookupKey: 'premium_plan', displayName: 'Premium Plan', metadata: $metadata);
```

</details>

<details open>
<summary><strong>Update</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Update display name
$offering = RevenueCat::offerings()->update('off_123', 'Updated Premium Plan');

// Update with metadata
$metadata = ['color' => 'green'];
$offering = RevenueCat::offerings()->update('off_123', null, null, $metadata);

// Update is_current flag
$offering = RevenueCat::offerings()->update('off_123', null, true);

// Convenience-style
$offering = RevenueCat::updateOffering('off_123', displayName: 'Updated Premium Plan', isCurrent: true, metadata: $metadata);
```

</details>

<details open>
<summary><strong>Delete</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$deleted = RevenueCat::offerings()->delete('off_123'); // bool

// Convenience-style
$deleted = RevenueCat::deleteOffering('off_123');
```

</details>

---

## See also
- [`DATA.md`](../../DATA.md)
- Official docs: [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2)
