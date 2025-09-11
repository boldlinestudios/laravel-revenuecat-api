<?php

namespace BoldLineStudios\RevenueCatApi\Data\Subscriptions;

use BoldLineStudios\RevenueCatApi\Data\Support\Payload;
use Illuminate\Http\Client\Response;

class ManagementUrlData
{
    /**
     * @param  array<string, mixed>  $raw
     */
    private function __construct(
        /** @var array<string, mixed> */
        private array $raw,
        private string $resourceType,
        private string $managementUrl,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $resourceType = Payload::requireNonEmptyString($payload, 'object', 'ManagementUrlData');
        $managementUrl = Payload::requireNonEmptyString($payload, 'management_url', 'ManagementUrlData');

        return new self($payload, $resourceType, $managementUrl);
    }

    public static function fromResponse(Response $response): self
    {
        $payload = $response->json();
        $payload = is_array($payload) ? $payload : [];

        /** @var array<string, mixed> $payload */
        return self::fromArray($payload);
    }

    public function getManagementUrl(): string
    {
        return $this->managementUrl;
    }

    public function getResourceType(): string
    {
        return $this->resourceType;
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
            'management_url' => $this->managementUrl,
            'raw' => $this->raw,
        ];
    }
}
