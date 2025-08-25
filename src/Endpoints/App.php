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
     * Example payload:
     * [
     *   'name' => 'My App',
     *   'type' => 'app_store',
     *   'app_store' => ['bundle_id' => 'com.example.app', ...],
     * ]
     *
     * @param  array{name: string, type: string} & array<string, mixed>  $data
     */
    public function create(array $data): AppData
    {
        return AppData::fromResponse($this->createRaw($data));
    }

    public function get(string $appId): AppData
    {
        return AppData::fromResponse($this->getRaw($appId));
    }

    /**
     * Update an app.
     *
     * @param  array{name: string} & array<string, mixed>  $data
     *
     * Example payload:
     * [
     *   'name' => 'My App',
     *   'app_store' => ['bundle_id' => 'com.example.app', ...],
     * ]
     */
    public function update(string $id, array $data): AppData
    {
        return AppData::fromResponse($this->updateRaw($id, $data));
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
