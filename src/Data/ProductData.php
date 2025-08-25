<?php

namespace BoldlineStudios\RevenueCatApi\Data;

use Illuminate\Http\Client\Response;

/**
 * Immutable DTO representing a RevenueCat Product resource.
 */
class ProductData
{
    /** @var array<string, mixed> */
    private array $raw;

    private string $id;

    private ?string $storeIdentifier;

    private ?string $type;

    /** @var array<string, mixed>|null */
    private ?array $subscription;

    /** @var array<string, mixed>|null */
    private ?array $oneTime;

    private ?int $createdAtMs;

    private ?string $appId;

    private ?AppData $app;

    private ?string $displayName;

    /**
     * @param  array<string, mixed>  $raw
     * @param  array<string, mixed>|null  $subscription
     * @param  array<string, mixed>|null  $oneTime
     */
    private function __construct(
        array $raw,
        string $id,
        ?string $storeIdentifier,
        ?string $type,
        ?array $subscription,
        ?array $oneTime,
        ?int $createdAtMs,
        ?string $appId,
        ?AppData $app,
        ?string $displayName,
    ) {
        $this->raw = $raw;
        $this->id = $id;
        $this->storeIdentifier = $storeIdentifier;
        $this->type = $type;
        $this->subscription = $subscription;
        $this->oneTime = $oneTime;
        $this->createdAtMs = $createdAtMs;
        $this->appId = $appId;
        $this->app = $app;
        $this->displayName = $displayName;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $id = isset($payload['id']) && is_string($payload['id']) ? $payload['id'] : '';
        if ($id === '') {
            throw new \InvalidArgumentException('ProductData requires a non-empty string id');
        }

        $storeIdentifier = isset($payload['store_identifier']) && is_string($payload['store_identifier'])
            ? $payload['store_identifier']
            : null;

        $type = isset($payload['type']) && is_string($payload['type']) ? $payload['type'] : null;

        $subscription = isset($payload['subscription']) && is_array($payload['subscription'])
            ? $payload['subscription']
            : null;

        $oneTime = isset($payload['one_time']) && is_array($payload['one_time'])
            ? $payload['one_time']
            : null;

        $createdAtMs = null;
        if (isset($payload['created_at'])) {
            $v = $payload['created_at'];
            if (is_int($v)) {
                $createdAtMs = $v;
            } elseif (is_string($v) && is_numeric($v)) {
                $createdAtMs = (int) $v;
            }
        }

        $appId = isset($payload['app_id']) && is_string($payload['app_id']) ? $payload['app_id'] : null;

        $app = null;
        if (isset($payload['app']) && is_array($payload['app'])) {
            $app = AppData::fromArray($payload['app']);
        }

        $displayName = isset($payload['display_name']) && is_string($payload['display_name'])
            ? $payload['display_name']
            : null;

        return new self(
            $payload,
            $id,
            $storeIdentifier,
            $type,
            $subscription,
            $oneTime,
            $createdAtMs,
            $appId,
            $app,
            $displayName,
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

    public function getStoreIdentifier(): ?string
    {
        return $this->storeIdentifier;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getSubscription(): ?array
    {
        return $this->subscription;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getOneTime(): ?array
    {
        return $this->oneTime;
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

    public function getAppId(): ?string
    {
        return $this->appId;
    }

    public function getApp(): ?AppData
    {
        return $this->app;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
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
            'store_identifier' => $this->storeIdentifier,
            'type' => $this->type,
            'subscription' => $this->subscription,
            'one_time' => $this->oneTime,
            'created_at' => $this->createdAtMs,
            'app_id' => $this->appId,
            'app' => $this->app?->toArray(),
            'display_name' => $this->displayName,
            'raw' => $this->raw,
        ];
    }
}
