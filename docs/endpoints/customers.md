# Customers

> For the official RevenueCat API reference, see [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2).

Manage RevenueCat customers, their attributes, and relationships with subscriptions, purchases, and entitlements.

**Related Types:** [`CustomerData`](../../DATA.md#customerdata), [`ListPage`](../../DATA.md#listpage), [`ActiveEntitlementData`](../../DATA.md#activeentitlementdata), [`AliasData`](../../DATA.md#aliasdata), [`AttributeData`](../../DATA.md#attributedata), [`VirtualCurrencyBalanceData`](../../DATA.md#virtualcurrencybalancedata)

---

## Methods

<details>
<summary><strong>Endpoint Methods</strong></summary>

<br>

**RevenueCat::customers()**

---
| Action | Signature | Returns |
|---|---|---|
| Get | `get(string $customerId)` | `CustomerData` |
| List | `all(int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<CustomerData>` |
| Create | `create(string $id, array $attributes)` | `CustomerData` |
| Delete | `delete(string $customerId)` | `bool` |
| List Subscriptions | `listOfSubscriptions(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<SubscriptionData>` |
| List Purchases | `listOfPurchases(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<PurchaseData>` |
| List Active Entitlements | `listOfActiveEntitlements(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<ActiveEntitlementData>` |
| List Aliases | `listOfAliases(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<AliasData>` |
| List Virtual Currency | `listOfVirtualCurrencyBalances(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<VirtualCurrencyBalanceData>` |
|List Attributes | `listOfAttributes(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<AttributeData>` |
| Set Attributes | `setAttributes(string $customerId, array $attributes)` | `ListPage<AttributeData>` |

</details>

<details>
<summary><strong>Convenience Methods</strong></summary>

<br>

| Action | Signature | Returns |
|---|---|---|
| Get | `getCustomer(string $customerId)` | `CustomerData` |
| List | `listCustomers(int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<CustomerData>` |
| Create | `createCustomer(string $id, array $attributes)` | `CustomerData` |
| Delete | `deleteCustomer(string $customerId)` | `bool` |
| Subscriptions | `listCustomerSubscriptions(string $customerId)` | `ListPage<SubscriptionData>` |
| Purchases | `listCustomerPurchases(string $customerId)` | `ListPage<PurchaseData>` |
| Active Entitlements | `listCustomerActiveEntitlements(string $customerId)` | `ListPage<ActiveEntitlementData>` |
| Aliases | `listCustomerAliases(string $customerId)` | `ListPage<AliasData>` |
| Virtual Currency | `listCustomerVirtualCurrencyBalances(string $customerId)` | `ListPage<VirtualCurrencyBalanceData>` |
| Attributes | `listCustomerAttributes(string $customerId)` | `ListPage<AttributeData>` |
| Set Attributes | `setCustomerAttributes(string $customerId, array $attributes)` | `ListPage<AttributeData>` |

</details>

---

## Parameters

<details>
<summary><strong>Create Parameters</strong></summary>

<br>

**Create**

| Name | Type | Required | Notes |
|---|---|:---:|---|
| `id` | `string` | ✓ | Customer identifier |
| `attributes` | `array<array{name: string, value: string}>` | ✓ | List of attribute objects |

> Each attribute must be an array with `name` and `value` string keys.
</details>

<details>
<summary><strong>Set Attributes Parameters</strong></summary>

<br>

**Set Attributes**

| Name | Type | Required | Notes |
|---|---|:---:|---|
| `customerId` | `string` | ✓ | Customer identifier |
| `attributes` | `array<array{name: string, value: string}>` | ✓ | List of attribute objects to set |

> Each attribute must be an array with `name` and `value` string keys.
</details>

---

## Examples

<details open>
<summary><strong>Get</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$customer = RevenueCat::customers()->get('cus_123');

// Convenience-style
$customer = RevenueCat::getCustomer('cus_123');
```

</details>

<details open>
<summary><strong>List</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$page = RevenueCat::customers()->all(limit: 20);

foreach ($page->items() as $customer) {
    // $customer is CustomerData
}

// Convenience-style
$page = RevenueCat::listCustomers(limit: 20);
```

</details>

<details open>
<summary><strong>Create</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$attributes = [
    ['name' => '$email', 'value' => 'user@example.com'],
];

// Endpoint-style
$customer = RevenueCat::customers()->create(id: 'cus_123', attributes: $attributes);

// Convenience-style
$customer = RevenueCat::createCustomer(id: 'cus_123', attributes: $attributes);
```

</details>

<details open>
<summary><strong>Delete</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$deleted = RevenueCat::customers()->delete('cus_123'); // bool

// Convenience-style
$deleted = RevenueCat::deleteCustomer('cus_123');
```

</details>

<details open>
<summary><strong>List Subscriptions</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$subscriptions = RevenueCat::customers()->listOfSubscriptions('cus_123', limit: 50);

// Convenience-style
$subscriptions = RevenueCat::listCustomerSubscriptions('cus_123', limit: 50);
```

</details>

<details open>
<summary><strong>List Purchases</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$purchases = RevenueCat::customers()->listOfPurchases('cus_123', limit: 50);

// Convenience-style
$purchases = RevenueCat::listCustomerPurchases('cus_123', limit: 50);
```

</details>

<details open>
<summary><strong>List Active Entitlements</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$entitlements = RevenueCat::customers()->listOfActiveEntitlements('cus_123', limit: 50);

// Convenience-style
$entitlements = RevenueCat::listCustomerActiveEntitlements('cus_123', limit: 50);
```

</details>

<details open>
<summary><strong>List Aliases</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$aliases = RevenueCat::customers()->listOfAliases('cus_123', limit: 50);

// Convenience-style
$aliases = RevenueCat::listCustomerAliases('cus_123', limit: 50);
```

</details>

<details open>
<summary><strong>List Virtual Currency Balances</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$balances = RevenueCat::customers()->listOfVirtualCurrencyBalances('cus_123', limit: 50);

// Convenience-style
$balances = RevenueCat::listCustomerVirtualCurrencyBalances('cus_123', limit: 50);
```

</details>

<details open>
<summary><strong>List Attributes</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$attributes = RevenueCat::customers()->listOfAttributes('cus_123', limit: 50);

// Convenience-style
$attributes = RevenueCat::listCustomerAttributes('cus_123', limit: 50);
```

</details>

<details open>
<summary><strong>Set Attributes</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$newAttributes = [
    ['name' => '$email', 'value' => 'support@example.com'],
    ['name' => '$displayName', 'value' => 'John Appleseed'],
];

// Endpoint-style
$attributes = RevenueCat::customers()->setAttributes('cus_123', $newAttributes);

// Convenience-style
$attributes = RevenueCat::setCustomerAttributes('cus_123', $newAttributes);

// The returned ListPage<AttributeData> contains the updated attributes
foreach ($attributes->items() as $attribute) {
    // $attribute is AttributeData
    echo $attribute->getName() . ': ' . $attribute->getValue() . "\n";
}
```

</details>

---

## See also
- [`DATA.md`](../../DATA.md)
- Official docs: [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2)
