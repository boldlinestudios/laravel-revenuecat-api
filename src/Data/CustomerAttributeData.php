<?php

namespace BoldlineStudios\RevenueCatApi\Data;

use BoldlineStudios\RevenueCatApi\Data\Support\Payload;
use Illuminate\Http\Client\Response;

class CustomerAttributeData
{
    /**
     * @param  array<string, mixed>  $raw
     */
    private function __construct(
        /** @var array<string, mixed> */
        private array $raw,
        private string $key,
        private ?string $value,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $key = Payload::requireNonEmptyString($payload, 'key', 'CustomerAttributeData');
        $value = isset($payload['value']) && is_string($payload['value']) ? $payload['value'] : null;

        return new self($payload, $key, $value);
    }

    public static function fromResponse(Response $response): self
    {
        $payload = $response->json();
        $payload = is_array($payload) ? $payload : [];

        /** @var array<string, mixed> $payload */
        return self::fromArray($payload);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): ?string
    {
        return $this->value;
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
            'key' => $this->key,
            'value' => $this->value,
            'raw' => $this->raw,
        ];
    }
}
