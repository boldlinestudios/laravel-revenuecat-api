<?php

namespace BoldLineStudios\RevenueCatApi\Endpoints;

use BoldLineStudios\RevenueCatApi\Data\ListPage;
use BoldLineStudios\RevenueCatApi\Data\ProductData;
use BoldLineStudios\RevenueCatApi\Endpoints\Concerns\Creatable;
use BoldLineStudios\RevenueCatApi\Endpoints\Concerns\Deletable;
use BoldLineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldLineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldLineStudios\RevenueCatApi\Http\RevenueCatClient;

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
    public function all(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        /** @var ListPage<ProductData> */
        return $this->listAsDto(ProductData::class, $limit, $startingAfter, $extra);
    }
}
