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
     *
     * @param array{
     *   store_identifier: string,
     *   app_id: string,
     *   type: 'subscription'|'one_time'|'consumable'|'non_consumable'|'non_renewing_subscription',
     *   display_name: string|null
     * } $data
     */
    public function create(array $data): ProductData
    {
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
