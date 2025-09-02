<?php

namespace BoldlineStudios\RevenueCatApi\Data;

use BoldlineStudios\RevenueCatApi\Data\Invoice\Amount;
use BoldlineStudios\RevenueCatApi\Data\Invoice\LineItem;
use BoldlineStudios\RevenueCatApi\Data\Support\Payload;
use Illuminate\Http\Client\Response;

/**
 * Represents an invoice from RevenueCat
 */
class InvoiceData
{
    /**
     * @param  array<string, mixed>  $raw
     * @param  LineItem[]  $lineItems
     */
    private function __construct(
        /** @var array<string, mixed> */
        private array $raw,
        private string $resourceType,
        private string $id,
        private Amount $totalAmount,
        /** @var LineItem[] */
        private array $lineItems,
        /** Milliseconds since epoch */
        private ?int $issuedAtMs,
        /** Milliseconds since epoch */
        private ?int $paidAtMs,
        private ?string $invoiceUrl,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $resourceType = Payload::requireNonEmptyString($payload, 'object', 'InvoiceData');
        $id = Payload::requireNonEmptyString($payload, 'id', 'InvoiceData');

        $totalAmount = Amount::fromArray($payload['total_amount'] ?? []);

        $lineItems = [];
        if (isset($payload['line_items']) && is_array($payload['line_items'])) {
            foreach ($payload['line_items'] as $lineItemData) {
                if (is_array($lineItemData)) {
                    $lineItems[] = LineItem::fromArray($lineItemData);
                }
            }
        }

        $issuedAtMs = Payload::parseMs($payload['issued_at'] ?? null);
        $paidAtMs = Payload::parseMs($payload['paid_at'] ?? null);
        $invoiceUrl = isset($payload['invoice_url']) && is_string($payload['invoice_url']) ? $payload['invoice_url'] : null;

        return new self(
            $payload,
            $resourceType,
            $id,
            $totalAmount,
            $lineItems,
            $issuedAtMs,
            $paidAtMs,
            $invoiceUrl,
        );
    }

    public static function fromResponse(Response $response): self
    {
        $payload = $response->json();
        $payload = is_array($payload) ? $payload : [];

        /** @var array<string, mixed> $payload */
        return self::fromArray($payload);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    public function getTotalAmount(): Amount
    {
        return $this->totalAmount;
    }

    /**
     * @return LineItem[]
     */
    public function getLineItems(): array
    {
        return $this->lineItems;
    }

    /** Milliseconds since epoch */
    public function getIssuedAtMs(): ?int
    {
        return $this->issuedAtMs;
    }

    public function getIssuedAtDate(): ?\DateTimeImmutable
    {
        return Payload::dateFromMs($this->issuedAtMs);
    }

    /** Milliseconds since epoch */
    public function getPaidAtMs(): ?int
    {
        return $this->paidAtMs;
    }

    public function getPaidAtDate(): ?\DateTimeImmutable
    {
        return Payload::dateFromMs($this->paidAtMs);
    }

    public function getInvoiceUrl(): ?string
    {
        return $this->invoiceUrl;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRaw(): array
    {
        return $this->raw;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'object' => $this->resourceType,
            'id' => $this->id,
            'total_amount' => $this->totalAmount->toArray(),
            'line_items' => array_map(fn (LineItem $item) => $item->toArray(), $this->lineItems),
            'issued_at' => $this->issuedAtMs,
            'paid_at' => $this->paidAtMs,
            'invoice_url' => $this->invoiceUrl,
            'raw' => $this->raw,
        ];
    }
}
