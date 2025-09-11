<?php

namespace BoldLineStudios\RevenueCatApi\Data\Invoice;

use BoldLineStudios\RevenueCatApi\Data\Support\Payload;

/**
 * Represents a line item in an invoice
 */
class LineItem
{
    private function __construct(
        private string $resourceType,
        private string $productIdentifier,
        private ?string $productDisplayName,
        private ?string $productDuration,
        private int $quantity,
        private Amount $unitAmount,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            Payload::requireNonEmptyString($data, 'object', 'InvoiceLineItem'),
            Payload::requireNonEmptyString($data, 'product_identifier', 'InvoiceLineItem'),
            Payload::requireNonEmptyString($data, 'product_display_name', 'InvoiceLineItem'),
            Payload::requireNonEmptyString($data, 'product_duration', 'InvoiceLineItem'),
            (int) ($data['quantity'] ?? 1),
            Amount::fromArray($data['unit_amount'] ?? []),
        );
    }

    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    public function getProductIdentifier(): string
    {
        return $this->productIdentifier;
    }

    public function getProductDisplayName(): ?string
    {
        return $this->productDisplayName;
    }

    public function getProductDuration(): ?string
    {
        return $this->productDuration;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getUnitAmount(): Amount
    {
        return $this->unitAmount;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'object' => $this->resourceType,
            'product_identifier' => $this->productIdentifier,
            'product_display_name' => $this->productDisplayName,
            'product_duration' => $this->productDuration,
            'quantity' => $this->quantity,
            'unit_amount' => $this->unitAmount->toArray(),
        ];
    }
}
