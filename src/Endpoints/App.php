<?php

namespace BoldLineStudios\RevenueCatApi\Endpoints;

use BoldLineStudios\RevenueCatApi\Data\App\PublicApiKeyData;
use BoldLineStudios\RevenueCatApi\Data\App\StoreKitConfigData;
use BoldLineStudios\RevenueCatApi\Data\AppData;
use BoldLineStudios\RevenueCatApi\Data\ListPage;
use BoldLineStudios\RevenueCatApi\Endpoints\Concerns\Creatable;
use BoldLineStudios\RevenueCatApi\Endpoints\Concerns\Deletable;
use BoldLineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldLineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldLineStudios\RevenueCatApi\Endpoints\Concerns\Updatable;
use BoldLineStudios\RevenueCatApi\Http\RevenueCatClient;

class App
{
    use Creatable;
    use Deletable;
    use Listable;
    use Retrievable;
    use Updatable;

    public function __construct(private RevenueCatClient $client) {}

    protected function client(): RevenueCatClient
    {
        return $this->client;
    }

    protected function basePath(): string
    {
        return '/apps';
    }

    /**
     * @return ListPage<PublicApiKeyData>
     */
    public function listOfPublicKeys(string $appId): ListPage
    {
        $appId = rawurlencode($appId);
        $path = "/apps/{$appId}/public_api_keys";

        /** @var ListPage<PublicApiKeyData> $result */
        $result = $this->listPageForPath($path, PublicApiKeyData::class);

        return $result;
    }

    /**
     * Get the StoreKit config for an app.
     *
     * @throws \InvalidArgumentException If the app is not an Apple app
     */
    public function getStoreKitConfig(string $appId): StoreKitConfigData
    {
        // Validate this is an Apple app first
        $app = $this->get($appId);

        if (! in_array($app->getType(), ['app_store', 'mac_app_store'])) {
            throw new \InvalidArgumentException('StoreKit config is only available for Apple apps');
        }

        $response = $this->client->get("/apps/{$appId}/store_kit_config");

        return StoreKitConfigData::fromResponse($response);
    }

    /**
     * Create an app.
     *
     * Provider-specific config lives under a key matching `type`.
     *
     * storeconfig top level must match the type. Example payload for $storeConfig:
     * [
     *   'play_store' => [
     *     'package_name' => 'com.example.app',
     *   ],
     * ]
     *
     * @param  array<string, array<string, mixed>>  $storeConfig
     */
    public function create(string $name, string $type, array $storeConfig): AppData
    {
        $this->validateCreatePayload($name, $type, $storeConfig);

        return AppData::fromResponse($this->createRaw([
            'name' => $name,
            'type' => $type,
            ...$storeConfig,
        ]));
    }

    /**
     * Validate the payload for the create method.
     *
     * @param  array<string, array<string, mixed>>  $storeConfig
     */
    private function validateCreatePayload(string $name, string $type, array $storeConfig): void
    {
        $types = ['amazon', 'app_store', 'mac_app_store', 'play_store', 'stripe', 'rc_billing', 'roku', 'paddle'];

        if ($name === '' || strlen($name) > 255) {
            throw new \InvalidArgumentException('name must be a non-empty string with a maximum length of 255 characters');
        }

        if (! in_array($type, $types)) {
            throw new \InvalidArgumentException('type must be one of: '.implode(', ', $types));
        }

        // Ensure storeConfig has exactly one top-level key and it matches the type
        $topLevelKeys = array_keys($storeConfig);
        if (count($topLevelKeys) !== 1 || $topLevelKeys[0] !== $type) {
            $provided = implode(', ', $topLevelKeys);
            throw new \InvalidArgumentException("storeConfig must contain exactly one top-level key matching type '$type'. Provided: [$provided]");
        }
    }

    public function get(string $appId): AppData
    {
        return AppData::fromResponse($this->getRaw($appId));
    }

    /**
     * Update an app.
     *
     * @param  ?array<string, array<string, mixed>>  $storeConfig
     *
     * Example payload for $storeConfig:
     * [
     *   'play_store' => [
     *     'bundle_id' => 'com.example.app',
     *     'shared_secret' => '1234567890',
     *   ],
     * ]
     */
    public function update(string $id, ?string $name = null, ?array $storeConfig = null): AppData
    {
        // if name is null, it will not be included in the payload
        if ($name && $storeConfig) {
            return AppData::fromResponse($this->updateRaw($id, [
                'name' => $name,
                ...$storeConfig,
            ]));
        }

        if ($storeConfig) {
            return AppData::fromResponse($this->updateRaw($id, [
                ...$storeConfig,
            ]));
        }

        if ($name) {
            return AppData::fromResponse($this->updateRaw($id, [
                'name' => $name,
            ]));
        }

        throw new \InvalidArgumentException('Either name or storeConfig must be provided');
    }

    /**
     * List apps.
     *
     * @param  array<string, mixed>  $extra
     * @return ListPage<AppData>
     */
    public function all(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        /** @var ListPage<AppData> $result */
        $result = $this->listAsDto(AppData::class, $limit, $startingAfter, $extra);

        return $result;
    }
}
