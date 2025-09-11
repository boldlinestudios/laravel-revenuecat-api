# Purchases

> For the official RevenueCat API reference, see [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2).


**Related Types:** [`PurchaseData`](../../DATA.md#purchasedata), [`EntitlementData`](../../DATA.md#entitlementdata), [`ListPage`](../../DATA.md#listpage)

---

## Methods

<details>
<summary><strong>Endpoint Methods</strong></summary>

<br>

**RevenueCat::purchases()**

---

| Action | Signature | Returns |
|---|---|---|
| Get | `get(string $purchaseId)` | `PurchaseData` |
| List Entitlements | `listOfEntitlements(string $purchaseId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<EntitlementData>` |
| Refund | `refundWebBillingPurchase(string $purchaseId)` | `PurchaseData` |
| Search by Identifier | `searchPurchasesByIdentifier(string $storePurchaseIdentifier)` | `ListPage<PurchaseData>` |

</details>

<details>
<summary><strong>Convenience Methods</strong></summary>

| Action | Signature | Returns |
|---|---|---|
| Get | `getPurchase(string $purchaseId)` | `PurchaseData` |
| List Entitlements | `listPurchaseEntitlements(string $purchaseId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<EntitlementData>` |
| Refund | `refundWebBillingPurchase(string $purchaseId)` | `PurchaseData` |
| Search by Identifier | `searchPurchasesByIdentifier(string $storePurchaseIdentifier)` | `ListPage<PurchaseData>` |

</details>

---

## Parameters

<details>
<summary><strong>Search by Identifier Parameters</strong></summary>

| Name | Type | Required | Notes |
|---|---|:---:|---|
| `storePurchaseIdentifier` | `string` | ✓ | Store-specific purchase identifier (e.g., transaction ID, order ID) |

</details>

---

## Examples

<details open>
<summary><strong>Get</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$purchase = RevenueCat::purchases()->get('pur_123');

// Convenience-style
$purchase = RevenueCat::getPurchase('pur_123');
```

</details>

<details open>
<summary><strong>List Entitlements</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$entitlements = RevenueCat::purchases()->listOfEntitlements('pur_123', limit: 50);

// Convenience-style
$entitlements = RevenueCat::listPurchaseEntitlements('pur_123', limit: 50);
```

</details>

<details open>
<summary><strong>Refund Web Billing Purchase</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$refundedPurchase = RevenueCat::purchases()->refundWebBillingPurchase('pur_123');

// Convenience-style
$refundedPurchase = RevenueCat::refundWebBillingPurchase('pur_123');
```

</details>

<details open>
<summary><strong>Search Purchases by Identifier</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

// Search by store purchase identifier
$purchases = RevenueCat::purchases()->searchPurchasesByIdentifier('1000001234567890');

// Convenience-style
$purchases = RevenueCat::searchPurchasesByIdentifier('1000001234567890');

foreach ($purchases->items() as $purchase) {
    // $purchase is PurchaseData
    echo $purchase->getId();
    echo $purchase->getStorePurchaseIdentifier();
}
```

</details>

---

## See also
- [`DATA.md`](../../DATA.md)
- Official docs: [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2)
