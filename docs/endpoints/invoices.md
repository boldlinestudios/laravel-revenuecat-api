# Invoices

> For the official RevenueCat API reference, see [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2).

Manage RevenueCat invoices for customers, including retrieving invoice history and details.

**Related Types:** [`InvoiceData`](../../DATA.md#invoicedata), [`Amount`](../../DATA.md#invoice-amount), [`LineItem`](../../DATA.md#invoice-lineitem), [`ListPage`](../../DATA.md#listpage)

---

## Methods

<details>
<summary><strong>Endpoint Methods</strong></summary>

<br>

**RevenueCat::invoices()**

---

| Action | Signature | Returns |
|---|---|---|
| List Customer Invoices | `listCustomerInvoices(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<InvoiceData>` |

</details>

<details>
<summary><strong>Convenience Methods</strong></summary>

<br>

| Action | Signature | Returns |
|---|---|---|
| List Customer Invoices | `listCustomerInvoices(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<InvoiceData>` |

</details>

---

## Parameters

<details>
<summary><strong>List Customer Invoices Parameters</strong></summary>

| Name | Type | Required | Notes |
|---|---|:---:|---|
| `customerId` | `string` | ✓ | Customer identifier |
| `limit` | `int` | Optional | Maximum number of invoices to return (default: 20) |
| `startingAfter` | `?string` | Optional | Cursor for pagination |
| `extra` | `array<string, mixed>` | Optional | Additional query parameters |

</details>

---

## Examples

<details open>
<summary><strong>List Customer Invoices</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$invoices = RevenueCat::invoices()->listCustomerInvoices('cus_123', limit: 20);

// Iterate through invoices
foreach ($invoices->items() as $invoice) {
    // $invoice is InvoiceData
    echo $invoice->getId();
    echo $invoice->getTotalAmount();
    echo $invoice->getIssuedAtDate()?->format('Y-m-d');
}

// Convenience-style
$invoices = RevenueCat::listCustomerInvoices('cus_123', limit: 20);
```

</details>

<details open>
<summary><strong>Access Invoice Details</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$invoices = RevenueCat::listCustomerInvoices('cus_123');

foreach ($invoices->items() as $invoice) {
    // Access invoice properties
    $id = $invoice->getId();
    $totalAmount = $invoice->getTotalAmount();
    $issuedAt = $invoice->getIssuedAtDate();
    $paidAt = $invoice->getPaidAtDate();
    $invoiceUrl = $invoice->getInvoiceUrl();

    // Access line items
    foreach ($invoice->getLineItems() as $lineItem) {
        // Process each line item
    }
}
```

</details>

---

## See also
- [`DATA.md`](../../DATA.md)
- Official docs: [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2)
