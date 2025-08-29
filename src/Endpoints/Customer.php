<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Data\CustomerActiveEntitlementData;
use BoldlineStudios\RevenueCatApi\Data\CustomerAliasData;
use BoldlineStudios\RevenueCatApi\Data\CustomerAttributeData;
use BoldlineStudios\RevenueCatApi\Data\CustomerData;
use BoldlineStudios\RevenueCatApi\Data\CustomerVirtualCurrencyBalanceData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Data\PurchaseData;
use BoldlineStudios\RevenueCatApi\Data\SubscriptionData;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Creatable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Deletable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Retrievable;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;

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
     * @param  array<string, mixed>  $extra
     * @return ListPage<SubscriptionData>
     */
    public function listOfSubscriptions(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        $customerId = rawurlencode($customerId);
        $path = "/customers/{$customerId}/subscriptions";

        /** @var ListPage<SubscriptionData> */
        return $this->listPageForPath($path, SubscriptionData::class, $limit, $startingAfter, $extra);
    }

    /**
     * Get a list of purchases associated with a customer
     *
     * @param  array<string, mixed>  $extra
     * @return ListPage<PurchaseData>
     */
    public function listOfPurchases(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        $customerId = rawurlencode($customerId);
        $path = "/customers/{$customerId}/purchases";

        /** @var ListPage<PurchaseData> */
        return $this->listPageForPath($path, PurchaseData::class, $limit, $startingAfter, $extra);
    }

    /**
     * Get a list of active entitlements for a customer
     */
    /**
     * @return ListPage<CustomerActiveEntitlementData>
     */
    public function listOfActiveEntitlements(string $customerId): ListPage
    {
        $customerId = rawurlencode($customerId);
        $path = "/customers/{$customerId}/active_entitlements";

        /** @var ListPage<CustomerActiveEntitlementData> */
        return $this->listPageForPath($path, CustomerActiveEntitlementData::class, 20, null, [], 'entitlements', 'next_page', 'url');
    }

    /**
     * Get a list of aliases for a customer
     */
    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<CustomerAliasData>
     */
    public function listOfAliases(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        $customerId = rawurlencode($customerId);
        $path = "/customers/{$customerId}/aliases";

        /** @var ListPage<CustomerAliasData> */
        return $this->listPageForPath($path, CustomerAliasData::class, $limit, $startingAfter, $extra);
    }

    /**
     * Get a list of virtual currency balances for the customer
     */
    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<CustomerVirtualCurrencyBalanceData>
     */
    public function listOfVirtualCurrencyBalances(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        $customerId = rawurlencode($customerId);
        $path = "/customers/{$customerId}/virtual_currencies";

        /** @var ListPage<CustomerVirtualCurrencyBalanceData> */
        return $this->listPageForPath($path, CustomerVirtualCurrencyBalanceData::class, $limit, $startingAfter, $extra, 'virtual_currencies');
    }

    /**
     * Get a list of customer attributes
     */
    /**
     * @param  array<string, mixed>  $extra
     * @return ListPage<CustomerAttributeData>
     */
    public function listOfAttributes(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        $customerId = rawurlencode($customerId);
        $path = "/customers/{$customerId}/attributes";

        /** @var ListPage<CustomerAttributeData> */
        return $this->listPageForPath($path, CustomerAttributeData::class, $limit, $startingAfter, $extra, 'attributes');
    }
}
