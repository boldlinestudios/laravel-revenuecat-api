# Projects

> For the official RevenueCat API reference, see [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2).

**Related Types:** [`ProjectData`](../../DATA.md#projectdata), [`ListPage`](../../DATA.md#listpage)

---

## Methods

<details>
<summary><strong>Endpoint Methods</strong></summary>

<br>

**RevenueCat::projects()**

---

| Action | Signature | Returns |
|---|---|---|
| List | `all(int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<ProjectData>` |

</details>

<details>
<summary><strong>Convenience Methods</strong></summary>

<br>

| Action | Signature | Returns |
|---|---|---|
| List | `listProjects(int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<ProjectData>` |

</details>

---

## Parameters

<details>
<summary><strong>List Parameters</strong></summary>

<br>

| Name | Type | Required | Notes |
|---|---|:---:|---|
| `limit` | `int` | Optional | Maximum number of projects to return (default: 20) |
| `startingAfter` | `?string` | Optional | Cursor for pagination |
| `extra` | `array<string, mixed>` | Optional | Additional query parameters |

</details>

---

## Examples

<details open>
<summary><strong>List</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$page = RevenueCat::projects()->all(limit: 20);

foreach ($page->items() as $project) {
    // $project is ProjectData
    echo $project->getName();
}

// Convenience-style
$page = RevenueCat::listProjects(limit: 20);
```

</details>

---

## See also
- [`DATA.md`](../../DATA.md)
- Official docs: [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2)
