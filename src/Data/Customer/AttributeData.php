<?php

namespace BoldLineStudios\RevenueCatApi\Data\Customer;

use BoldLineStudios\RevenueCatApi\Data\Support\Payload;
use Illuminate\Http\Client\Response;

class AttributeData
{
    /**
     * @param  array<string, mixed>  $raw
     */
    private function __construct(
        /** @var array<string, mixed> */
        private array $raw,
        private string $resourceType,
        private string $name,
        private ?string $value,
        private ?int $updatedAtMs,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $resourceType = Payload::requireNonEmptyString($payload, 'object', 'AttributeData');
        $name = Payload::requireNonEmptyString($payload, 'name', 'AttributeData');
        $value = isset($payload['value']) && is_string($payload['value']) ? $payload['value'] : null;
        $updatedAtMs = Payload::parseMs($payload['updated_at'] ?? null);

        return new self($payload, $resourceType, $name, $value, $updatedAtMs);
    }

    public static function fromResponse(Response $response): self
    {
        $payload = $response->json();
        $payload = is_array($payload) ? $payload : [];

        /** @var array<string, mixed> $payload */
        return self::fromArray($payload);
    }

    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getUpdatedAtMs(): ?int
    {
        return $this->updatedAtMs;
    }

    public function getUpdatedAtDate(): ?\DateTimeImmutable
    {
        return Payload::dateFromMs($this->updatedAtMs);
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
            'name' => $this->name,
            'value' => $this->value,
            'updated_at' => $this->updatedAtMs,
            'raw' => $this->raw,
        ];
    }
}
