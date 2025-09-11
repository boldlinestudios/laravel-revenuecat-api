<?php

namespace BoldLineStudios\RevenueCatApi\Data;

use BoldLineStudios\RevenueCatApi\Data\Support\Payload;
use Illuminate\Http\Client\Response;

class ProjectData
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
        /** Milliseconds since epoch, if provided by the API */
        private ?int $createdAtMs,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $resourceType = Payload::requireNonEmptyString($payload, 'object', 'ProjectData');
        $id = Payload::requireNonEmptyString($payload, 'id', 'ProjectData');

        $name = isset($payload['name']) && is_string($payload['name']) ? $payload['name'] : null;

        $createdAtMs = Payload::parseMs($payload['created_at'] ?? null);

        return new self(
            $payload,
            $resourceType,
            $id,
            $name,
            $createdAtMs,
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

    /** Milliseconds since epoch, if provided by the API */
    public function getCreatedAtMs(): ?int
    {
        return $this->createdAtMs;
    }

    public function getCreatedAtDate(): ?\DateTimeImmutable
    {
        return Payload::dateFromMs($this->createdAtMs);
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
            'created_at' => $this->createdAtMs,
            'raw' => $this->raw,
        ];
    }
}
