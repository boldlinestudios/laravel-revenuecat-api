<?php

namespace BoldlineStudios\RevenueCatApi\Data;

use Illuminate\Http\Client\Response;

/**
 * Immutable DTO representing a RevenueCat Offering resource.
 */
class OfferingData
{
    /**
     * @param  array<string, mixed>  $raw
     * @param  array<string, mixed>|null  $metadata
     * @param  array<string, mixed>|null  $packages
     */
    private function __construct(
        /** @var array<string, mixed> */
        private array $raw,
        private string $id,
        private ?string $lookupKey,
        private ?string $displayName,
        private ?bool $isCurrent,
        private ?int $createdAtMs,
        private ?string $projectId,
        /** @var array<string, mixed>|null */
        private ?array $metadata,
        /** @var array<string, mixed>|null */
        private ?array $packages,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $id = isset($payload['id']) && is_string($payload['id']) ? $payload['id'] : '';
        if ($id === '') {
            throw new \InvalidArgumentException('OfferingData requires a non-empty string id');
        }

        $lookupKey = isset($payload['lookup_key']) && is_string($payload['lookup_key']) ? $payload['lookup_key'] : null;
        $displayName = isset($payload['display_name']) && is_string($payload['display_name']) ? $payload['display_name'] : null;

        $isCurrent = null;
        if (array_key_exists('is_current', $payload)) {
            $isCurrent = (bool) $payload['is_current'];
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

        $projectId = isset($payload['project_id']) && is_string($payload['project_id']) ? $payload['project_id'] : null;
        $metadata = isset($payload['metadata']) && is_array($payload['metadata']) ? $payload['metadata'] : null;
        $packages = isset($payload['packages']) && is_array($payload['packages']) ? $payload['packages'] : null;

        return new self(
            $payload,
            $id,
            $lookupKey,
            $displayName,
            $isCurrent,
            $createdAtMs,
            $projectId,
            $metadata,
            $packages,
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

    public function isCurrent(): ?bool
    {
        return $this->isCurrent;
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

    public function getProjectId(): ?string
    {
        return $this->projectId;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    /**
     * Packages list payload as returned by the API (object="list").
     *
     * @return array<string, mixed>|null
     */
    public function getPackages(): ?array
    {
        return $this->packages;
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
            'is_current' => $this->isCurrent,
            'created_at' => $this->createdAtMs,
            'project_id' => $this->projectId,
            'metadata' => $this->metadata,
            'packages' => $this->packages,
            'raw' => $this->raw,
        ];
    }
}
