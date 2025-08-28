<?php

namespace BoldlineStudios\RevenueCatApi\Data;

use Illuminate\Http\Client\Response;

/**
 * Immutable DTO representing a RevenueCat Project resource.
 * Projects are currently only listed, not retrieved individually in this API.
 */
class ProjectData
{
    /**
     * @param  array<string, mixed>  $raw
     */
    private function __construct(
        /** @var array<string, mixed> */
        private array $raw,
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
        $id = isset($payload['id']) && is_string($payload['id']) ? $payload['id'] : '';
        if ($id === '') {
            throw new \InvalidArgumentException('ProjectData requires a non-empty string id');
        }

        $name = isset($payload['name']) && is_string($payload['name']) ? $payload['name'] : null;

        $createdAtMs = null;
        if (isset($payload['created_at'])) {
            $createdAt = $payload['created_at'];
            if (is_int($createdAt)) {
                $createdAtMs = $createdAt;
            } elseif (is_string($createdAt) && is_numeric($createdAt)) {
                $createdAtMs = (int) $createdAt;
            }
        }

        return new self(
            $payload,
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
        if ($this->createdAtMs === null) {
            return null;
        }

        return (new \DateTimeImmutable('@0'))
            ->setTimestamp((int) floor($this->createdAtMs / 1000))
            ->setTimezone(new \DateTimeZone('UTC'));
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
            'name' => $this->name,
            'created_at' => $this->createdAtMs,
            'raw' => $this->raw,
        ];
    }
}
