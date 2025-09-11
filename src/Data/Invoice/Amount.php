<?php

namespace BoldLineStudios\RevenueCatApi\Data\Invoice;

use BoldLineStudios\RevenueCatApi\Data\Support\Payload;

/**
 * Represents an invoice amount with currency and financial details
 */
class Amount
{
    private function __construct(
        private string $resourceType,
        private string $currency,
        private float $gross,
        private ?float $commission,
        private float $tax,
        private float $proceeds,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            'MonetaryAmount',
            Payload::requireNonEmptyString($data, 'currency', 'InvoiceAmount'),
            (float) ($data['gross'] ?? 0),
            (float) ($data['commission'] ?? 0),
            (float) ($data['tax'] ?? 0),
            (float) ($data['proceeds'] ?? 0),
        );
    }

    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getGross(): float
    {
        return $this->gross;
    }

    public function getCommission(): ?float
    {
        return $this->commission;
    }

    public function getTax(): float
    {
        return $this->tax;
    }

    public function getProceeds(): float
    {
        return $this->proceeds;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'object' => $this->resourceType,
            'currency' => $this->currency,
            'gross' => $this->gross,
            'commission' => $this->commission,
            'tax' => $this->tax,
            'proceeds' => $this->proceeds,
        ];
    }
}
