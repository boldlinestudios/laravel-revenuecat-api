# Apps

> For the official RevenueCat API reference, see [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2).

Manage RevenueCat apps and their store-specific configuration.

**Related Types:** [`AppData`](../../DATA.md#appdata), [`PublicApiKeyData`](../../DATA.md#publicapikeydata), [`StoreKitConfigData`](../../DATA.md#storekitconfigdata), [`ListPage`](../../DATA.md#listpage)

---

## Methods

<details>
<summary><strong>Endpoint Methods</strong></summary>
<br>

**RevenueCat::apps()**

---

| Action | Signature | Returns |
|---|---|---|
| Get | `get(string $appId)` | `AppData` |
| List | `all(int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<AppData>` |
| Create | `create(string $name, string $type, array $storeConfig)` | `AppData` |
| Update | `update(string $id, ?string $name = null, ?array $storeConfig = null)` | `AppData` |
| Delete | `delete(string $appId)` | `bool` |
| Public Keys | `listOfPublicKeys(string $appId)` | `ListPage<PublicApiKeyData>` |
| StoreKit Config | `getStoreKitConfig(string $appId)` | `StoreKitConfigData` |

</details>

<details>
<summary><strong>Convenience Methods</strong></summary>
<br>

| Action | Signature | Returns |
|---|---|---|
| Get | `getApp(string $appId)` | `AppData` |
| List | `listApps(int $limit = 20, ?string $startingAfter = null, array $extra = [])` | `ListPage<AppData>` |
| Create | `createApp(string $name, string $type, array $storeConfig)` | `AppData` |
| Update | `updateApp(string $appId, ?string $name = null, ?array $storeConfig = [])` | `AppData` |
| Delete | `deleteApp(string $appId)` | `bool` |
| Public Keys | `listAppPublicKeys(string $appId)` | `ListPage<PublicApiKeyData>` |
| StoreKit Config | `getAppStoreKitConfig(string $appId)` | `StoreKitConfigData` |

</details>

---

## Parameters

<details>
<summary><strong>Create Parameters</strong></summary>

| Name | Type | Required | Notes |
|---|---|:---:|---|
| `name` | `string` | ✓ | Display name |
| `type` | `string` | ✓ | e.g. `app_store`, `play_store` |
| `storeConfig` | `array<string,mixed>` | ✓ | Store-specific keys (see examples) |

</details>

<details>
<summary><strong>Update Parameters</strong></summary>

| Name | Type | Required | Notes |
|---|---|:---:|---|
| `name` | `string` | Optional | Display name (can be null) |
| `storeConfig` | `array<string,mixed>` | Optional | Store-specific keys (can be null) |

> **Note:** At least one of `name` or `storeConfig` must be provided.

</details>


---

## Examples

<details open>
<summary><strong>Get</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$app = RevenueCat::apps()->get('app_123');

// Convenience-style
$app = RevenueCat::getApp('app_123');
```

</details>

<details open>
<summary><strong>List</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$page = RevenueCat::apps()->all(limit: 20);

foreach ($page->items() as $app) {
    // $app is AppData
}

// Convenience-style
$page = RevenueCat::listApps(limit: 20);
```

</details>

<details open>
<summary><strong>Create</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

$storeConfig = [
    'play_store' => [
        'package_name' => 'com.example.app',
    ],
];

// Endpoint-style
$app = RevenueCat::apps()->create(name: 'My App', type: 'play_store', storeConfig: $storeConfig);

// Convenience-style
$app = RevenueCat::createApp(name: 'My App', type: 'play_store', storeConfig: $storeConfig);
```

</details>

<details open>
<summary><strong>Update</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Update name and store configuration
$app = RevenueCat::apps()->update('app_123', name: 'New Name', storeConfig: [
    'play_store' => [
        'bundle_id' => 'com.example.app',
        'shared_secret' => '1234567890abcdef1234567890abcdef',
    ],
]);

// Update only store configuration
$app = RevenueCat::apps()->update('app_123', storeConfig: [
    'play_store' => [
        'bundle_id' => 'com.example.app',
        'shared_secret' => '1234567890abcdef1234567890abcdef',
    ],
]);

// Update only name
$app = RevenueCat::apps()->update('app_123', name: 'New Name');

// Convenience-style
$app = RevenueCat::updateApp('app_123', name: 'New Name', storeConfig: [
    'play_store' => [
        'bundle_id' => 'com.example.app',
        'shared_secret' => '1234567890abcdef1234567890abcdef',
    ],
]);
```

</details>

<details open>
<summary><strong>Delete</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$deleted = RevenueCat::apps()->delete('app_123'); // bool

// Convenience-style
$deleted = RevenueCat::deleteApp('app_123');
```

</details>

<details open>
<summary><strong>Public Keys</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$keys = RevenueCat::apps()->listOfPublicKeys('app_123'); // ListPage<PublicApiKeyData>

// Convenience-style
$keys = RevenueCat::listAppPublicKeys('app_123');
```

</details>

<details open>
<summary><strong>StoreKit Config</strong></summary>

```php
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;

// Endpoint-style
$config = RevenueCat::apps()->getStoreKitConfig('app_123');

// Convenience-style
$config = RevenueCat::getAppStoreKitConfig('app_123');

// Access the configuration contents
$contents = $config->getContents();
$resourceType = $config->getResourceType(); // "store_kit_config_file"
```

</details>

---

## See also
- [`DATA.md`](../../DATA.md)
- Official docs: [RevenueCat API v2 Docs](https://www.revenuecat.com/docs/api-v2)
