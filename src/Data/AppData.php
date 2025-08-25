<?php

namespace BoldlineStudios\RevenueCatApi\Data;

use Illuminate\Http\Client\Response;

/**
 * Immutable DTO representing a RevenueCat App resource.
 */
class AppData
{
    /** @var array<string, mixed> */
    private array $raw;

    private string $id;

    private ?string $name;

    /** Milliseconds since epoch, if provided by the API */
    private ?int $createdAtMs;

    private ?string $type;

    private ?string $projectId;

    /** @var array<string, mixed>|null */
    private ?array $amazon;

    /** @var array<string, mixed>|null */
    private ?array $appStore;

    /** @var array<string, mixed>|null */
    private ?array $macAppStore;

    /** @var array<string, mixed>|null */
    private ?array $playStore;

    /** @var array<string, mixed>|null */
    private ?array $stripe;

    /** @var array<string, mixed>|null */
    private ?array $rcBilling;

    /** @var array<string, mixed>|null */
    private ?array $roku;

    /** @var array<string, mixed>|null */
    private ?array $paddle;

    /**
     * @param  array<string, mixed>  $raw
     * @param  array<string, mixed>|null  $amazon
     * @param  array<string, mixed>|null  $appStore
     * @param  array<string, mixed>|null  $macAppStore
     * @param  array<string, mixed>|null  $playStore
     * @param  array<string, mixed>|null  $stripe
     * @param  array<string, mixed>|null  $rcBilling
     * @param  array<string, mixed>|null  $roku
     * @param  array<string, mixed>|null  $paddle
     */
    private function __construct(
        array $raw,
        string $id,
        ?string $name,
        ?int $createdAtMs,
        ?string $type,
        ?string $projectId,
        ?array $amazon,
        ?array $appStore,
        ?array $macAppStore,
        ?array $playStore,
        ?array $stripe,
        ?array $rcBilling,
        ?array $roku,
        ?array $paddle,
    ) {
        $this->raw = $raw;
        $this->id = $id;
        $this->name = $name;
        $this->createdAtMs = $createdAtMs;
        $this->type = $type;
        $this->projectId = $projectId;
        $this->amazon = $amazon;
        $this->appStore = $appStore;
        $this->macAppStore = $macAppStore;
        $this->playStore = $playStore;
        $this->stripe = $stripe;
        $this->rcBilling = $rcBilling;
        $this->roku = $roku;
        $this->paddle = $paddle;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $id = isset($payload['id']) && is_string($payload['id']) ? $payload['id'] : '';
        if ($id === '') {
            throw new \InvalidArgumentException('AppData requires a non-empty string id');
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

        $type = isset($payload['type']) && is_string($payload['type']) ? $payload['type'] : null;
        $projectId = isset($payload['project_id']) && is_string($payload['project_id']) ? $payload['project_id'] : null;

        $amazon = isset($payload['amazon']) && is_array($payload['amazon']) ? $payload['amazon'] : null;
        $appStore = isset($payload['app_store']) && is_array($payload['app_store']) ? $payload['app_store'] : null;
        $macAppStore = isset($payload['mac_app_store']) && is_array($payload['mac_app_store']) ? $payload['mac_app_store'] : null;
        $playStore = isset($payload['play_store']) && is_array($payload['play_store']) ? $payload['play_store'] : null;
        $stripe = isset($payload['stripe']) && is_array($payload['stripe']) ? $payload['stripe'] : null;
        $rcBilling = isset($payload['rc_billing']) && is_array($payload['rc_billing']) ? $payload['rc_billing'] : null;
        $roku = isset($payload['roku']) && is_array($payload['roku']) ? $payload['roku'] : null;
        $paddle = isset($payload['paddle']) && is_array($payload['paddle']) ? $payload['paddle'] : null;

        return new self(
            $payload,
            $id,
            $name,
            $createdAtMs,
            $type,
            $projectId,
            $amazon,
            $appStore,
            $macAppStore,
            $playStore,
            $stripe,
            $rcBilling,
            $roku,
            $paddle,
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

        // created_at is documented in ms
        return (new \DateTimeImmutable('@0'))
            ->setTimestamp((int) floor($this->createdAtMs / 1000))
            ->setTimezone(new \DateTimeZone('UTC'));
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getProjectId(): ?string
    {
        return $this->projectId;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAmazon(): ?array
    {
        return $this->amazon;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAppStore(): ?array
    {
        return $this->appStore;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getMacAppStore(): ?array
    {
        return $this->macAppStore;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getPlayStore(): ?array
    {
        return $this->playStore;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getStripe(): ?array
    {
        return $this->stripe;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRcBilling(): ?array
    {
        return $this->rcBilling;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRoku(): ?array
    {
        return $this->roku;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getPaddle(): ?array
    {
        return $this->paddle;
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
            'type' => $this->type,
            'project_id' => $this->projectId,
            'amazon' => $this->amazon,
            'app_store' => $this->appStore,
            'mac_app_store' => $this->macAppStore,
            'play_store' => $this->playStore,
            'stripe' => $this->stripe,
            'rc_billing' => $this->rcBilling,
            'roku' => $this->roku,
            'paddle' => $this->paddle,
            'raw' => $this->raw,
        ];
    }
}
