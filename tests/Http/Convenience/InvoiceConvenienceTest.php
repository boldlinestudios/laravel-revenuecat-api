<?php

use BoldlineStudios\RevenueCatApi\Data\Invoice\LineItem;
use BoldlineStudios\RevenueCatApi\Data\InvoiceData;
use BoldlineStudios\RevenueCatApi\Data\ListPage;
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

test('listCustomerInvoices calls invoices()->listCustomerInvoices() with correct parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/invoices' => Http::response([
            'object' => 'list',
            'items' => [
                [
                    'object' => 'invoice',
                    'id' => 'inv_1234567890abcdef',
                    'total_amount' => [
                        'currency' => 'USD',
                        'gross' => 9.99,
                        'commission' => 1.99,
                        'tax' => 0.50,
                        'proceeds' => 7.50,
                    ],
                    'line_items' => [
                        [
                            'object' => 'invoice.line_item',
                            'product_identifier' => 'rc_1w_199',
                            'product_display_name' => 'Basic Monthly 2023',
                            'product_duration' => 'P1M',
                            'quantity' => 1,
                            'unit_amount' => [
                                'currency' => 'USD',
                                'gross' => 9.99,
                                'commission' => 1.99,
                                'tax' => 0.50,
                                'proceeds' => 7.50,
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

    $invoices = RevenueCat::listCustomerInvoices('test-customer-id');

    expect($invoices)->toBeInstanceOf(ListPage::class);
    expect(count($invoices->items()))->toBe(1);
    expect($invoices->items()[0])->toBeInstanceOf(InvoiceData::class);
    expect($invoices->items()[0]->getId())->toBe('inv_1234567890abcdef');
    expect($invoices->items()[0]->getTotalAmount()->getGross())->toBe(9.99);
    expect($invoices->items()[0]->getTotalAmount()->getCurrency())->toBe('USD');
    expect(count($invoices->items()[0]->getLineItems()))->toBe(1);
    expect($invoices->items()[0]->getLineItems()[0])->toBeInstanceOf(LineItem::class);
    expect($invoices->items()[0]->getLineItems()[0]->getProductIdentifier())->toBe('rc_1w_199');
    expect($invoices->items()[0]->getLineItems()[0]->getProductDisplayName())->toBe('Basic Monthly 2023');
    expect($invoices->items()[0]->getLineItems()[0]->getProductDuration())->toBe('P1M');
    expect($invoices->items()[0]->getLineItems()[0]->getQuantity())->toBe(1);
    expect($invoices->items()[0]->getLineItems()[0]->getUnitAmount()->getGross())->toBe(9.99);
    expect($invoices->items()[0]->getLineItems()[0]->getUnitAmount()->getCurrency())->toBe('USD');
    expect($invoices->items()[0]->getIssuedAtMs())->toBe(1658399423658);
    expect($invoices->items()[0]->getIssuedAtDate())->toBeInstanceOf(\DateTimeImmutable::class);
    expect($invoices->items()[0]->getPaidAtMs())->toBe(1658399423658);
    expect($invoices->items()[0]->getInvoiceUrl())->toBe('https://api.revenuecat.com/v2/projects/proj1ab2c3d4/customers/cust1ab2c3d4/invoices/inv1ab2c3d4/file');
});

test('listCustomerInvoices works with limit and startingAfter parameters', function () {
    Http::fake([
        'https://api.example.com/v2/projects/test_project/customers/test-customer-id/invoices?limit=10&starting_after=inv_abc123' => Http::response([
            'object' => 'list',
            'items' => [],
            'next_page' => null,
            'url' => '/v2/projects/test_project/customers/test-customer-id/invoices?limit=10&starting_after=inv_abc123',
        ], 200),
    ]);

    $invoices = RevenueCat::listCustomerInvoices('test-customer-id', 10, 'inv_abc123');

    expect($invoices)->toBeInstanceOf(ListPage::class);
    expect(count($invoices->items()))->toBe(0);
});
