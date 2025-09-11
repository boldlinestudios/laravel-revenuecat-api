<?php

namespace BoldLineStudios\RevenueCatApi\Data\App;

use BoldLineStudios\RevenueCatApi\Data\Support\Payload;
use Illuminate\Http\Client\Response;

class StoreKitConfigData
{
    /**
     * @param  array<string, mixed>  $raw
     */
    private function __construct(
        private string $resourceType,
        /** @var array<string, mixed> */
        private array $contents,
        /** @var array<string, mixed> */
        private array $raw,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $resourceType = Payload::requireNonEmptyString($payload, 'object', 'StoreKitConfigData');

        if ($resourceType !== 'store_kit_config_file') {
            throw new \InvalidArgumentException('Invalid StoreKit config object type');
        }

        return new self(
            $resourceType,
            $payload['contents'] ?? [],
            $payload,
        );
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

    /**
     * @return array<string, mixed>
     */
    public function getContents(): array
    {
        return $this->contents;
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
            'contents' => $this->contents,
            'raw' => $this->raw,
        ];
    }
}
