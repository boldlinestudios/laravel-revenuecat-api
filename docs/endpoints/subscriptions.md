# Subscriptions

> For the official RevenueCat API reference, see [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2).

**Related Types:** [`SubscriptionData`](../../DATA.md#subscriptiondata), [`EntitlementData`](../../DATA.md#entitlementdata), [`TransactionData`](../../DATA.md#transactiondata), [`ManagementUrlData`](../../DATA.md#managementurldata), [`ListPage`](../../DATA.md#listpage)

---

## Methods

<details>
<summary><strong>Endpoint Methods</strong></summary>

**RevenueCat::subscriptions()**

---

| Action | Signature | Returns |
|---|---|---|
| Get | `get(string $subscriptionId)` | `SubscriptionData` |
| List Entitlements | `listOfEntitlements(string $subscriptionId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<EntitlementData>` |
| List Transactions | `listOfTransactions(string $subscriptionId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<TransactionData>` |
| Get Customer Portal URL | `getCustomerPortalUrl(string $subscriptionId)` | `ManagementUrlData` |
| Cancel Web Billing | `cancelWebBillingSubscription(string $subscriptionId)` | `SubscriptionData` |
| Refund Web Billing | `refundWebBillingSubscription(string $subscriptionId)` | `SubscriptionData` |
| Refund Play Store Transaction | `refundPlayStoreSubscriptionTransaction(string $subscriptionId, string $transactionId)` | `TransactionData` |

</details>

<details>
<summary><strong>Convenience Methods</strong></summary>

| Action | Signature | Returns |
|---|---|---|
| Get | `getSubscription(string $subscriptionId)` | `SubscriptionData` |
| List Entitlements | `listSubscriptionEntitlements(string $subscriptionId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<EntitlementData>` |
| List Transactions | `listSubscriptionTransactions(string $subscriptionId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<TransactionData>` |
| Get Customer Portal URL | `getSubscriptionCustomerPortalUrl(string $subscriptionId)` | `ManagementUrlData` |
| Cancel Web Billing | `cancelWebBillingSubscription(string $subscriptionId)` | `SubscriptionData` |
| Refund Web Billing | `refundWebBillingSubscription(string $subscriptionId)` | `SubscriptionData` |
| Refund Play Store Transaction | `refundPlayStoreSubscriptionTransaction(string $subscriptionId, string $transactionId)` | `TransactionData` |

</details>

---

## Parameters

<details>
<summary><strong>Refund Play Store Transaction Parameters</strong></summary>

| Name | Type | Required | Notes |
|---|---|:---:|---|
| `subscriptionId` | `string` | ✓ | The subscription identifier |
| `transactionId` | `string` | ✓ | The specific transaction to refund |

</details>

---

## Examples

<details open>
<summary><strong>Get</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$subscription = RevenueCat::subscriptions()->get('sub_123');

// Convenience-style
$subscription = RevenueCat::getSubscription('sub_123');
```

</details>

<details open>
<summary><strong>List Entitlements</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$entitlements = RevenueCat::subscriptions()->listOfEntitlements('sub_123', limit: 50);

// Convenience-style
$entitlements = RevenueCat::listSubscriptionEntitlements('sub_123', limit: 50);
```

</details>

<details open>
<summary><strong>List Transactions</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$transactions = RevenueCat::subscriptions()->listOfTransactions('sub_123', limit: 50);

// Convenience-style
$transactions = RevenueCat::listSubscriptionTransactions('sub_123', limit: 50);
```

</details>

<details open>
<summary><strong>Get Customer Portal URL</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$managementUrl = RevenueCat::subscriptions()->getCustomerPortalUrl('sub_123');

// Convenience-style
$managementUrl = RevenueCat::getSubscriptionCustomerPortalUrl('sub_123');

// Access the secure, single-use URL for customer portal access
$portalUrl = $managementUrl->getManagementUrl();

// The URL can be provided to customers for managing their subscription
echo "Customer Portal: {$portalUrl}";
```

</details>

<details open>
<summary><strong>Cancel Web Billing Subscription</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Cancel subscription (customer loses access at end of current period)
// Endpoint-style
$subscription = RevenueCat::subscriptions()->cancelWebBillingSubscription('sub_123');

// Convenience-style
$subscription = RevenueCat::cancelWebBillingSubscription('sub_123');
```

</details>

<details open>
<summary><strong>Refund Web Billing Subscription</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Refund most recent payment (customer immediately loses access)
// Endpoint-style
$subscription = RevenueCat::subscriptions()->refundWebBillingSubscription('sub_123');

// Convenience-style
$subscription = RevenueCat::refundWebBillingSubscription('sub_123');
```

</details>

<details open>
<summary><strong>Refund Play Store Subscription Transaction</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Refund specific Play Store transaction (does not cancel subscription)
// Endpoint-style
$transaction = RevenueCat::subscriptions()->refundPlayStoreSubscriptionTransaction('sub_123', 'txn_456');

// Convenience-style
$transaction = RevenueCat::refundPlayStoreSubscriptionTransaction('sub_123', 'txn_456');
```

</details>

---

## See also
- [`DATA.md`](../../DATA.md)
- Official docs: [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2)
