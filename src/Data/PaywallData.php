<?php

namespace BoldLineStudios\RevenueCatApi\Data;

use BoldLineStudios\RevenueCatApi\Data\Support\Payload;
use Illuminate\Http\Client\Response;

class PaywallData
{
    /**
     * @param  array<string, mixed>  $raw
     */
    private function __construct(
        /** @var array<string, mixed> */
        private array $raw,
        private string $resourceType,
        private string $id,
        private ?string $name,
        private string $offeringId,
        /** Milliseconds since epoch */
        private ?int $createdAtMs,
        /** Milliseconds since epoch, if published */
        private ?int $publishedAtMs,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $resourceType = Payload::requireNonEmptyString($payload, 'object', 'PaywallData');
        $id = Payload::requireNonEmptyString($payload, 'id', 'PaywallData');
        $offeringId = Payload::requireNonEmptyString($payload, 'offering_id', 'PaywallData');

        $name = isset($payload['name']) && is_string($payload['name']) ? $payload['name'] : null;

        $createdAtMs = Payload::parseMs($payload['created_at'] ?? null);
        $publishedAtMs = Payload::parseMs($payload['published_at'] ?? null);

        return new self(
            $payload,
            $resourceType,
            $id,
            $name,
            $offeringId,
            $createdAtMs,
            $publishedAtMs,
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getOfferingId(): string
    {
        return $this->offeringId;
    }

    /** Milliseconds since epoch */
    public function getCreatedAtMs(): ?int
    {
        return $this->createdAtMs;
    }

    public function getCreatedAtDate(): ?\DateTimeImmutable
    {
        return Payload::dateFromMs($this->createdAtMs);
    }

    /** Milliseconds since epoch, if published */
    public function getPublishedAtMs(): ?int
    {
        return $this->publishedAtMs;
    }

    public function getPublishedAtDate(): ?\DateTimeImmutable
    {
        return Payload::dateFromMs($this->publishedAtMs);
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
            'name' => $this->name,
            'offering_id' => $this->offeringId,
            'created_at' => $this->createdAtMs,
            'published_at' => $this->publishedAtMs,
            'raw' => $this->raw,
        ];
    }
}
