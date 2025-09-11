<?php

use BoldLineStudios\RevenueCatApi\Data\InvoiceData;
use BoldLineStudios\RevenueCatApi\Data\ListPage;
use BoldLineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

test('listCustomerInvoices returns ListPage of InvoiceData', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/invoices' => Http::response([
            'object' => 'list',
            'items' => [
                [
                    'object' => 'invoice',
                    'id' => 'inv_1234567890abcdef',
                    'total_amount' => [
                        'currency' => 'USD',
                        'gross' => 19.99,
                        'commission' => 3.99,
                        'tax' => 1.00,
                        'proceeds' => 15.00,
                    ],
                    'line_items' => [
                        [
                            'object' => 'invoice.line_item',
                            'product_identifier' => 'rc_1w_199',
                            'product_display_name' => 'Premium Monthly 2023',
                            'product_duration' => 'P1M',
                            'quantity' => 1,
                            'unit_amount' => [
                                'currency' => 'USD',
                                'gross' => 19.99,
                                'commission' => 3.99,
                                'tax' => 1.00,
                                'proceeds' => 15.00,
                            ],
                        ],
                    ],
                    'issued_at' => 1658399423658,
                    'paid_at' => 1658399423658,
                    'invoice_url' => 'https://api.revenuecat.com/v2/projects/proj1ab2c3d4/customers/cust1ab2c3d4/invoices/inv1ab2c3d4/file',
                ],
            ],
            'next_page' => null,
            'url' => '/v2/projects/test_project/customers/test-customer-id/invoices',
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $invoices = RevenueCat::invoices()->listCustomerInvoices($customerId);

    expect($invoices)->toBeInstanceOf(ListPage::class);
    expect(count($invoices->items()))->toBe(1);
    expect($invoices->items()[0])->toBeInstanceOf(InvoiceData::class);
    expect($invoices->items()[0]->getId())->toBe('inv_1234567890abcdef');
    expect($invoices->items()[0]->getTotalAmount()->getGross())->toBe(19.99);
    expect($invoices->items()[0]->getTotalAmount()->getCurrency())->toBe('USD');
    expect(count($invoices->items()[0]->getLineItems()))->toBe(1);
    expect($invoices->items()[0]->getLineItems()[0]->getProductIdentifier())->toBe('rc_1w_199');
});

test('listCustomerInvoices method properly encodes special characters in customer id', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test%20customer%20with%20spaces%20%26%20special%20chars/invoices' => Http::response([
            'object' => 'list',
            'items' => [],
            'next_page' => null,
            'url' => '/v2/projects/test_project/customers/test%20customer%20with%20spaces%20%26%20special%20chars/invoices',
        ], 200),
    ]);

    $customerId = 'test customer with spaces & special chars';
    $invoices = RevenueCat::invoices()->listCustomerInvoices($customerId);

    expect($invoices)->toBeInstanceOf(ListPage::class);
    expect(count($invoices->items()))->toBe(0);
    expect($invoices->items())->toBe([]);
    expect($invoices->nextCursor())->toBeNull();
});

test('listCustomerInvoices works with limit and startingAfter parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/invoices?limit=5&starting_after=inv_abc123' => Http::response([
            'object' => 'list',
            'items' => [],
            'next_page' => null,
            'url' => '/v2/projects/test_project/customers/test-customer-id/invoices?limit=5&starting_after=inv_abc123',
        ], 200),
    ]);

    $customerId = 'test-customer-id';
    $invoices = RevenueCat::invoices()->listCustomerInvoices($customerId, 5, 'inv_abc123');

    expect($invoices)->toBeInstanceOf(ListPage::class);
    expect(count($invoices->items()))->toBe(0);
});
