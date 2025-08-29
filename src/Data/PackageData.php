<?php

namespace BoldlineStudios\RevenueCatApi\Data;

use BoldlineStudios\RevenueCatApi\Data\Support\Payload;
use Illuminate\Http\Client\Response;

class PackageData
{
    /**
     * @param  array<string, mixed>  $raw
     * @param  array<string, mixed>|null  $products
     */
    private function __construct(
        /** @var array<string, mixed> */
        private array $raw,
        private string $id,
        private ?string $lookupKey,
        private ?string $displayName,
        private ?int $position,
        private ?int $createdAtMs,
        /** @var array<string, mixed>|null */
        private ?array $products,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $id = isset($payload['id']) && is_string($payload['id']) ? $payload['id'] : '';
        if ($id === '') {
            throw new \InvalidArgumentException('PackageData requires a non-empty string id');
        }

        $lookupKey = isset($payload['lookup_key']) && is_string($payload['lookup_key']) ? $payload['lookup_key'] : null;
        $displayName = isset($payload['display_name']) && is_string($payload['display_name']) ? $payload['display_name'] : null;

        $position = null;
        if (array_key_exists('position', $payload)) {
            $v = $payload['position'];
            if (is_int($v)) {
                $position = $v;
            } elseif (is_string($v) && is_numeric($v)) {
                $position = (int) $v;
            }
        }

        $createdAtMs = null;
        if (isset($payload['created_at'])) {
            $v = $payload['created_at'];
            if (is_int($v)) {
                $createdAtMs = $v;
            } elseif (is_string($v) && is_numeric($v)) {
                $createdAtMs = (int) $v;
            }
        }

        $products = isset($payload['products']) && is_array($payload['products']) ? $payload['products'] : null;

        return new self(
            $payload,
            $id,
            $lookupKey,
            $displayName,
            $position,
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

    public function getLookupKey(): ?string
    {
        return $this->lookupKey;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function getPosition(): ?int
    {
        return $this->position;
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
     * Products list payload as returned by the API (object="list").
     *
     * @return array<string, mixed>|null
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
            'lookup_key' => $this->lookupKey,
            'display_name' => $this->displayName,
            'position' => $this->position,
            'created_at' => $this->createdAtMs,
            'products' => $this->products,
            'raw' => $this->raw,
        ];
    }
}
