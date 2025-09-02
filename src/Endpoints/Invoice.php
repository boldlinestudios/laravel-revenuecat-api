<?php

namespace BoldlineStudios\RevenueCatApi\Endpoints;

use BoldlineStudios\RevenueCatApi\Data\InvoiceData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldlineStudios\RevenueCatApi\Http\RevenueCatClient;

class Invoice
{
    use Listable;

    public function __construct(private RevenueCatClient $client) {}

    protected function client(): RevenueCatClient
    {
        return $this->client;
    }

    protected function basePath(): string
    {
        return '/invoices';
    }

    /**
     * Get a list of the customer's invoices
     *
     * @param  array<string, mixed>  $extra
     * @return ListPage<InvoiceData>
     */
    public function listCustomerInvoices(string $customerId, int $limit = 20, ?string $startingAfter = null, array $extra = []): ListPage
    {
        $customerId = rawurlencode($customerId);
        $path = "/customers/{$customerId}/invoices";

        /** @var ListPage<InvoiceData> */
        return $this->listPageForPath($path, InvoiceData::class, $limit, $startingAfter, $extra);
    }
}
