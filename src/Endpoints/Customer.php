<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Data\CustomerData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Creatable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Deletable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;
use Illuminate\Http\Client\Response;

class Customer
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
        return '/customers';
    }

    /**
     * @param  array<int|string, mixed>  $attributes
     * @return list<array{name: string, value: string}>
     */
    private function validateAttributes(array $attributes): array
    {
        if (! array_is_list($attributes)) {
            throw new \InvalidArgumentException('Attributes must be a list of {name, value} items.');
        }

        foreach ($attributes as $item) {
            if (! is_array($item)
                || ! array_key_exists('name', $item)
                || ! array_key_exists('value', $item)
                || ! is_string($item['name'])
                || ! is_string($item['value'])
            ) {
                throw new \InvalidArgumentException('Each attribute must be an array with string keys "name" and "value".');
            }
        }

        return $attributes;
    }

    /**
     * Create a customer.
     *
     * @param  list<array{name: string, value: string}>  $attributes
     */
    public function create(string $id, array $attributes): CustomerData
    {
        return CustomerData::fromResponse(
            $this->createRaw([
                'id' => $id,
                'attributes' => $this->validateAttributes($attributes),
            ])
        );
    }

    public function get(string $customerId): CustomerData
    {
        return CustomerData::fromResponse($this->getRaw($customerId));
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<CustomerData>
     */
    public function list(int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        /** @var ListPage<CustomerData> */
        return $this->listAsDto(CustomerData::class, $limit, $startingAfter, $extra);
    }

    /**
     * Get a list of subscriptions associated with a customer
     */
    public function listOfSubscriptions(string $customerId): Response
    {
        $customerId = rawurlencode($customerId);

        return $this->client->get("customers/{$customerId}/subscriptions");
    }

    /**
     * Get a list of purchases associated with a customer
     */
    public function listOfPurchases(string $customerId): Response
    {
        $customerId = rawurlencode($customerId);

        return $this->client->get("customers/{$customerId}/purchases");
    }

    /**
     * Get a list of active entitlements for a customer
     */
    public function listOfActiveEntitlements(string $customerId): Response
    {
        $customerId = rawurlencode($customerId);

        return $this->client->get("customers/{$customerId}/active_entitlements");
    }

    /**
     * Get a list of aliases for a customer
     */
    public function listOfAliases(string $customerId): Response
    {
        $customerId = rawurlencode($customerId);

        return $this->client->get("customers/{$customerId}/aliases");
    }

    /**
     * Get a list of virtual currency balances for the customer
     */
    public function listOfVirtualCurrencyBalances(string $customerId): Response
    {
        $customerId = rawurlencode($customerId);

        return $this->client->get("customers/{$customerId}/virtual_currencies");
    }

    /**
     * Get a list of customer attributes
     */
    public function listOfAttributes(string $customerId): Response
    {
        $customerId = rawurlencode($customerId);

        return $this->client->get("customers/{$customerId}/attributes");
    }
}
