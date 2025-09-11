# Paywalls

> For the official RevenueCat API reference, see [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2).



**Related Types:** [`PaywallData`](../../DATA.md#paywalldata), [`ListPage`](../../DATA.md#listpage)

---

## Methods

<details>
<summary><strong>Endpoint Methods</strong></summary>

<br>

**RevenueCat::paywalls()**

---

| Action | Signature | Returns |
|---|---|---|
| Create | `create(string $offeringId)` | `PaywallData` |

</details>

<details>
<summary><strong>Convenience Methods</strong></summary>

| Action | Signature | Returns |
|---|---|---|
| Create | `createPaywall(string $offeringId)` | `PaywallData` |

</details>

---

## Parameters

<details>
<summary><strong>Create Parameters</strong></summary>

| Name | Type | Required | Notes |
|---|---|:---:|---|
| `offeringId` | `string` | ✓ | The offering ID to create a paywall for |

</details>

---

## Examples

<details open>
<summary><strong>Create</strong></summary>

```php
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$paywall = RevenueCat::paywalls()->create(offeringId: 'off_123');

// Convenience-style
$paywall = RevenueCat::createPaywall('off_123');
```

</details>

---

## See also
- [`DATA.md`](../../DATA.md)
- Official docs: [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2)
