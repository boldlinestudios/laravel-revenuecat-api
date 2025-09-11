<?php

namespace BoldLineStudios\RevenueCatApi\Endpoints;

use BoldLineStudios\RevenueCatApi\Data\InvoiceData;
use BoldLineStudios\RevenueCatApi\Data\ListPage;
use BoldLineStudios\RevenueCatApi\Endpoints\Concerns\Listable;
use BoldLineStudios\RevenueCatApi\Http\RevenueCatClient;

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
