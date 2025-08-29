<?php

namespace BoldlineStudios\RevenueCatApi\Data;

use BoldlineStudios\RevenueCatApi\Data\Support\Payload;
use Illuminate\Http\Client\Response;

class CustomerActiveEntitlementData
{
    /**
     * @param  array<string, mixed>  $raw
     */
    private function __construct(
        /** @var array<string, mixed> */
        private array $raw,
        private string $id,
        private ?string $identifier,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $id = Payload::requireNonEmptyString($payload, 'id', 'CustomerActiveEntitlementData');
        $identifier = isset($payload['identifier']) && is_string($payload['identifier'])
            ? $payload['identifier']
            : null;

        return new self($payload, $id, $identifier);
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

    public function getIdentifier(): ?string
    {
        return $this->identifier;
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
            'id' => $this->id,
            'identifier' => $this->identifier,
            'raw' => $this->raw,
        ];
    }
}
