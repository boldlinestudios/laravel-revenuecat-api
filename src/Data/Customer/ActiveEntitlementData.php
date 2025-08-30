<?php

namespace BoldlineStudios\RevenueCatApi\Data\Customer;

use BoldlineStudios\RevenueCatApi\Data\Support\Payload;
use Illuminate\Http\Client\Response;

class ActiveEntitlementData
{
    /**
     * @param  array<string, mixed>  $raw
     */
    private function __construct(
        /** @var array<string, mixed> */
        private array $raw,
        private string $entitlementId,
        private ?int $expiresAtMs,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $entitlementId = Payload::requireNonEmptyString($payload, 'entitlement_id', 'ActiveEntitlementData');
        $expiresAtMs = isset($payload['expires_at']) && is_int($payload['expires_at'])
            ? $payload['expires_at']
            : null;

        return new self($payload, $entitlementId, $expiresAtMs);
    }

    public static function fromResponse(Response $response): self
    {
        $payload = $response->json();
        $payload = is_array($payload) ? $payload : [];

        /** @var array<string, mixed> $payload */
        return self::fromArray($payload);
    }

    public function getEntitlementId(): string
    {
        return $this->entitlementId;
    }

    public function getExpiresAtMs(): ?int
    {
        return $this->expiresAtMs;
    }

    public function getExpiresAtDate(): ?\DateTimeImmutable
    {
        return Payload::dateFromMs($this->expiresAtMs) ?? null;
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
            'entitlement_id' => $this->entitlementId,
            'expires_at' => $this->expiresAtMs,
            'raw' => $this->raw,
        ];
    }
}
