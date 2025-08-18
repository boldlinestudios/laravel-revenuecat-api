<?php

namespace BoldlineStudios\RevenueCatApi\Models;

class Entitlement
{
    public function __construct(
        public string $identifier,
        public bool $isActive,
        public ?string $expiresDate,
        public string $productIdentifier,
        public string $purchaseDate
    ) {}

    /**
     * @param array{
     *   identifier?: string,
     *   is_active?: bool,
     *   expires_date?: string|null,
     *   product_identifier?: string,
     *   purchase_date?: string
     * } $data
    */
    public static function fromArray(array $data): self
    {
        return new self(
            identifier: $data['identifier'] ?? '',
            isActive: $data['is_active'] ?? false,
            expiresDate: $data['expires_date'] ?? null,
            productIdentifier: $data['product_identifier'] ?? '',
            purchaseDate: $data['purchase_date'] ?? ''
        );
    }

    /**
     * @return array{
     *   identifier: string,
     *   is_active: bool,
     *   expires_date: string|null,
     *   product_identifier: string,
     *   purchase_date: string
     * }
    */
    public function toArray(): array
    {
        return [
            'identifier' => $this->identifier,
            'is_active' => $this->isActive,
            'expires_date' => $this->expiresDate,
            'product_identifier' => $this->productIdentifier,
            'purchase_date' => $this->purchaseDate,
        ];
    }
}
