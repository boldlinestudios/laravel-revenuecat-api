<?php

namespace BoldlineStudios\RevenueCatApi\Data\Subscriptions;

use BoldlineStudios\RevenueCatApi\Data\Support\Payload;
use Illuminate\Http\Client\Response;

/**
 * Represents a subscription transaction from RevenueCat
 */
class TransactionData
{
    /**
     * @param  array<string, mixed>  $raw
     */
    private function __construct(
        /** @var array<string, mixed> */
        private array $raw,
        private string $resourceType,
        private string $id,
        /** Milliseconds since epoch */
        private ?int $purchasedAtMs,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $resourceType = Payload::requireNonEmptyString($payload, 'object', 'TransactionData');
        $id = Payload::requireNonEmptyString($payload, 'id', 'TransactionData');

        $purchasedAtMs = Payload::parseMs($payload['purchased_at'] ?? null);

        return new self(
            $payload,
            $resourceType,
            $id,
            $purchasedAtMs,
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

    /** Milliseconds since epoch */
    public function getPurchasedAtMs(): ?int
    {
        return $this->purchasedAtMs;
    }

    public function getPurchasedAtDate(): ?\DateTimeImmutable
    {
        return Payload::dateFromMs($this->purchasedAtMs);
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
            'purchased_at' => $this->purchasedAtMs,
            'raw' => $this->raw,
        ];
    }
}
