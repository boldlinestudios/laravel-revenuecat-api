<?php

namespace BoldlineStudios\RevenueCatApi\Data;

use Illuminate\Http\Client\Response;

/**
 * Immutable DTO representing a RevenueCat Entitlement resource.
 */
class EntitlementData
{
    /**
     * @param  array<string, mixed>  $raw
     * @param  array<int, ProductData>|null  $products
     */
    private function __construct(
        /** @var array<string, mixed> */
        private array $raw,
        private string $id,
        private ?string $projectId,
        private ?string $lookupKey,
        private ?string $displayName,
        private ?int $createdAtMs,
        /** @var array<int, ProductData>|null */
        private ?array $products,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $id = isset($payload['id']) && is_string($payload['id']) ? $payload['id'] : '';
        if ($id === '') {
            throw new \InvalidArgumentException('EntitlementData requires a non-empty string id');
        }

        $projectId = isset($payload['project_id']) && is_string($payload['project_id']) ? $payload['project_id'] : null;
        $lookupKey = isset($payload['lookup_key']) && is_string($payload['lookup_key']) ? $payload['lookup_key'] : null;
        $displayName = isset($payload['display_name']) && is_string($payload['display_name']) ? $payload['display_name'] : null;

        $createdAtMs = null;
        if (isset($payload['created_at'])) {
            $v = $payload['created_at'];
            if (is_int($v)) {
                $createdAtMs = $v;
            } elseif (is_string($v) && is_numeric($v)) {
                $createdAtMs = (int) $v;
            }
        }

        $products = null;
        if (isset($payload['products']) && is_array($payload['products'])) {
            $items = $payload['products']['items'] ?? [];
            $items = is_array($items) ? $items : [];
            $mapped = [];
            foreach ($items as $item) {
                if (is_array($item)) {
                    /** @var array<string, mixed> $item */
                    $mapped[] = ProductData::fromArray($item);
                }
            }
            $products = $mapped;
        }

        return new self(
            $payload,
            $id,
            $projectId,
            $lookupKey,
            $displayName,
            $createdAtMs,
            $products,
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

    public function getProjectId(): ?string
    {
        return $this->projectId;
    }

    public function getLookupKey(): ?string
    {
        return $this->lookupKey;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function getCreatedAtMs(): ?int
    {
        return $this->createdAtMs;
    }

    public function getCreatedAtDate(): ?\DateTimeImmutable
    {
        if ($this->createdAtMs === null) {
            return null;
        }

        return (new \DateTimeImmutable('@0'))
            ->setTimestamp((int) floor($this->createdAtMs / 1000))
            ->setTimezone(new \DateTimeZone('UTC'));
    }

    /**
     * @return array<int, ProductData>|null
     */
    public function getProducts(): ?array
    {
        return $this->products;
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
            'project_id' => $this->projectId,
            'lookup_key' => $this->lookupKey,
            'display_name' => $this->displayName,
            'created_at' => $this->createdAtMs,
            'products' => $this->products === null
                ? null
                : array_map(static function (ProductData $product): array {
                    return $product->toArray();
                }, $this->products),
            'raw' => $this->raw,
        ];
    }
}
