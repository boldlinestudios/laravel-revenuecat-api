<?php

namespace BoldlineStudios\RevenueCatApi\Data;

use Illuminate\Http\Client\Response;

/**
 * Immutable DTO representing a RevenueCat Customer resource.
 */
class CustomerData
{
    /** @var array<string, mixed> */
    private array $raw;

    private string $id;

    private ?string $projectId;

    private ?int $firstSeenAtMs;

    private ?int $lastSeenAtMs;

    private ?string $lastSeenAppVersion;

    private ?string $lastSeenCountry;

    private ?string $lastSeenPlatform;

    private ?string $lastSeenPlatformVersion;

    /** @var array<string, mixed>|null */
    private ?array $activeEntitlements;

    /** @var array<string, mixed>|null */
    private ?array $experiment;

    /** @var array<string, mixed>|null */
    private ?array $attributes;

    /**
     * @param  array<string, mixed>  $raw
     * @param  array<string, mixed>|null  $activeEntitlements
     * @param  array<string, mixed>|null  $experiment
     * @param  array<string, mixed>|null  $attributes
     */
    private function __construct(
        array $raw,
        string $id,
        ?string $projectId,
        ?int $firstSeenAtMs,
        ?int $lastSeenAtMs,
        ?string $lastSeenAppVersion,
        ?string $lastSeenCountry,
        ?string $lastSeenPlatform,
        ?string $lastSeenPlatformVersion,
        ?array $activeEntitlements,
        ?array $experiment,
        ?array $attributes,
    ) {
        $this->raw = $raw;
        $this->id = $id;
        $this->projectId = $projectId;
        $this->firstSeenAtMs = $firstSeenAtMs;
        $this->lastSeenAtMs = $lastSeenAtMs;
        $this->lastSeenAppVersion = $lastSeenAppVersion;
        $this->lastSeenCountry = $lastSeenCountry;
        $this->lastSeenPlatform = $lastSeenPlatform;
        $this->lastSeenPlatformVersion = $lastSeenPlatformVersion;
        $this->activeEntitlements = $activeEntitlements;
        $this->experiment = $experiment;
        $this->attributes = $attributes;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $id = isset($payload['id']) && is_string($payload['id']) ? $payload['id'] : '';
        if ($id === '') {
            throw new \InvalidArgumentException('CustomerData requires a non-empty string id');
        }

        $projectId = isset($payload['project_id']) && is_string($payload['project_id']) ? $payload['project_id'] : null;

        $firstSeenAtMs = null;
        if (isset($payload['first_seen_at'])) {
            $v = $payload['first_seen_at'];
            if (is_int($v)) {
                $firstSeenAtMs = $v;
            } elseif (is_string($v) && is_numeric($v)) {
                $firstSeenAtMs = (int) $v;
            }
        }

        $lastSeenAtMs = null;
        if (isset($payload['last_seen_at'])) {
            $v = $payload['last_seen_at'];
            if (is_int($v)) {
                $lastSeenAtMs = $v;
            } elseif (is_string($v) && is_numeric($v)) {
                $lastSeenAtMs = (int) $v;
            }
        }

        $lastSeenAppVersion = isset($payload['last_seen_app_version']) && is_string($payload['last_seen_app_version'])
            ? $payload['last_seen_app_version']
            : null;
        $lastSeenCountry = isset($payload['last_seen_country']) && is_string($payload['last_seen_country'])
            ? $payload['last_seen_country']
            : null;
        $lastSeenPlatform = isset($payload['last_seen_platform']) && is_string($payload['last_seen_platform'])
            ? $payload['last_seen_platform']
            : null;
        $lastSeenPlatformVersion = isset($payload['last_seen_platform_version']) && is_string($payload['last_seen_platform_version'])
            ? $payload['last_seen_platform_version']
            : null;

        $activeEntitlements = isset($payload['active_entitlements']) && is_array($payload['active_entitlements'])
            ? $payload['active_entitlements']
            : null;
        $experiment = isset($payload['experiment']) && is_array($payload['experiment'])
            ? $payload['experiment']
            : null;
        $attributes = isset($payload['attributes']) && is_array($payload['attributes'])
            ? $payload['attributes']
            : null;

        return new self(
            $payload,
            $id,
            $projectId,
            $firstSeenAtMs,
            $lastSeenAtMs,
            $lastSeenAppVersion,
            $lastSeenCountry,
            $lastSeenPlatform,
            $lastSeenPlatformVersion,
            $activeEntitlements,
            $experiment,
            $attributes,
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

    public function getFirstSeenAtMs(): ?int
    {
        return $this->firstSeenAtMs;
    }

    public function getFirstSeenAtDate(): ?\DateTimeImmutable
    {
        if ($this->firstSeenAtMs === null) {
            return null;
        }

        return (new \DateTimeImmutable('@0'))
            ->setTimestamp((int) floor($this->firstSeenAtMs / 1000))
            ->setTimezone(new \DateTimeZone('UTC'));
    }

    public function getLastSeenAtMs(): ?int
    {
        return $this->lastSeenAtMs;
    }

    public function getLastSeenAtDate(): ?\DateTimeImmutable
    {
        if ($this->lastSeenAtMs === null) {
            return null;
        }

        return (new \DateTimeImmutable('@0'))
            ->setTimestamp((int) floor($this->lastSeenAtMs / 1000))
            ->setTimezone(new \DateTimeZone('UTC'));
    }

    public function getLastSeenAppVersion(): ?string
    {
        return $this->lastSeenAppVersion;
    }

    public function getLastSeenCountry(): ?string
    {
        return $this->lastSeenCountry;
    }

    public function getLastSeenPlatform(): ?string
    {
        return $this->lastSeenPlatform;
    }

    public function getLastSeenPlatformVersion(): ?string
    {
        return $this->lastSeenPlatformVersion;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getActiveEntitlements(): ?array
    {
        return $this->activeEntitlements;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getExperiment(): ?array
    {
        return $this->experiment;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAttributes(): ?array
    {
        return $this->attributes;
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
            'first_seen_at' => $this->firstSeenAtMs,
            'last_seen_at' => $this->lastSeenAtMs,
            'last_seen_app_version' => $this->lastSeenAppVersion,
            'last_seen_country' => $this->lastSeenCountry,
            'last_seen_platform' => $this->lastSeenPlatform,
            'last_seen_platform_version' => $this->lastSeenPlatformVersion,
            'active_entitlements' => $this->activeEntitlements,
            'experiment' => $this->experiment,
            'attributes' => $this->attributes,
            'raw' => $this->raw,
        ];
    }
}
