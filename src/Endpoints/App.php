<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Data\AppData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Creatable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Deletable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Updatable;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

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

    public function listOfPublicKeys(string $appId): Response
    {
        $appId = rawurlencode($appId);

        return $this->client->get("/apps/{$appId}/public_api_keys");
    }

    /**
     * Get the StoreKit config for an app
     */
    public function storeKitConfig(string $appId): Response
    {
        return $this->client->get("/apps/{$appId}/store_kit_config");
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
     * @param  array<string, array<string, mixed>>  $storeConfig
     *
     * Example payload for $storeConfig:
     * [
     *   'play_store' => [
     *     'bundle_id' => 'com.example.app',
     *     'shared_secret' => '1234567890',
     *   ],
     * ]
     */
    public function update(string $id, ?string $name, array $storeConfig): AppData
    {
        // if name is null, it will not be included in the payload
        if ($name) {
            return AppData::fromResponse($this->updateRaw($id, [
                'name' => $name,
                ...$storeConfig,
            ]));
        }

        return AppData::fromResponse($this->updateRaw($id, [
            ...$storeConfig,
        ]));
    }

    /**
     * List apps.
     *
     * @param  array<string, mixed>  $extra
     * @return ListPage<AppData>
     */
    public function list(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->listAsDto(AppData::class, $limit, $startingAfter, $extra);
    }
}
