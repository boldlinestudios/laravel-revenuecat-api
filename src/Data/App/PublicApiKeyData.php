<?php

namespace BoldLineStudios\RevenueCatApi\Data\App;

use BoldLineStudios\RevenueCatApi\Data\Support\Payload;
use Illuminate\Http\Client\Response;

class PublicApiKeyData
{
    /**
     * @param  array<string, mixed>  $raw
     */
    private function __construct(
        /** @var array<string, mixed> */
        private array $raw,
        private string $resourceType,
        private string $id,
        private string $key,
        private string $environment,
        private string $appId,
        private ?int $createdAtMs,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $resourceType = Payload::requireNonEmptyString($payload, 'object', 'PublicApiKeyData');
        $id = Payload::requireNonEmptyString($payload, 'id', 'PublicApiKeyData');
        $key = Payload::requireNonEmptyString($payload, 'key', 'PublicApiKeyData');
        $environment = Payload::requireNonEmptyString($payload, 'environment', 'PublicApiKeyData');
        $appId = Payload::requireNonEmptyString($payload, 'app_id', 'PublicApiKeyData');
        $createdAtMs = Payload::parseMs($payload['created_at'] ?? null);

        return new self($payload, $resourceType, $id, $key, $environment, $appId, $createdAtMs);
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

    public function getKey(): string
    {
        return $this->key;
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function getAppId(): string
    {
        return $this->appId;
    }

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
            'key' => $this->key,
            'environment' => $this->environment,
            'app_id' => $this->appId,
            'created_at' => $this->createdAtMs,
            'raw' => $this->raw,
        ];
    }
}
