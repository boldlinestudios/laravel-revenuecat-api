<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\ProductData;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Creatable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Deletable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;

class Product
{
    use Creatable;
    use Deletable;
    use Listable;
    use Retrievable;

    public function __construct(private RevenueCatClient $client) {}

    protected function client(): RevenueCatClient
    {
        return $this->client;
    }

    protected function basePath(): string
    {
        return '/products';
    }

    /**
     * Create a product.
     */
    public function create(string $storeIdentifier, string $appId, string $type, ?string $displayName = null): ProductData
    {
        $data = [
            'store_identifier' => $storeIdentifier,
            'app_id' => $appId,
            'type' => $type,
        ];
        if ($displayName) {
            $data['display_name'] = $displayName;
        }

        return ProductData::fromResponse($this->createRaw($data));
    }

    public function get(string $appId): ProductData
    {
        return ProductData::fromResponse($this->getRaw($appId));
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<ProductData>
     */
    public function list(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        return $this->listAsDto(ProductData::class, $limit, $startingAfter, $extra);
    }
}
