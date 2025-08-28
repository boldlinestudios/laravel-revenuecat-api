<?php

namespace BoldlineStudios\RevenueCatApi\Data;

use Illuminate\Http\Client\Response;

/**
 * Immutable DTO representing a RevenueCat Subscription resource.
 */
class SubscriptionData
{
    /**
     * @param  array<string, mixed>  $raw
     * @param  array<string, mixed>|null  $totalRevenueInUsd
     * @param  array<string, mixed>|null  $entitlements
     */
    private function __construct(
        /** @var array<string, mixed> */
        private array $raw,
        private string $id,
        private ?string $customerId,
        private ?string $originalCustomerId,
        private ?string $productId,
        /** Milliseconds since epoch */
        private ?int $startsAtMs,
        /** Milliseconds since epoch */
        private ?int $currentPeriodStartsAtMs,
        /** Milliseconds since epoch */
        private ?int $currentPeriodEndsAtMs,
        private ?bool $givesAccess,
        private ?bool $pendingPayment,
        private ?string $autoRenewalStatus,
        private ?string $status,
        /** @var array<string, mixed>|null */
        private ?array $totalRevenueInUsd,
        private ?string $presentedOfferingId,
        /** @var array<string, mixed>|null */
        private ?array $entitlements,
        private ?string $environment,
        private ?string $store,
        /** Often numeric or string depending on store; normalize to string when possible */
        private ?string $storeSubscriptionIdentifier,
        private ?string $ownership,
        private ?ProductData $pendingChangesProduct = null,
        private ?string $country = null,
        private ?string $managementUrl = null,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $id = isset($payload['id']) && is_string($payload['id']) ? $payload['id'] : '';
        if ($id === '') {
            throw new \InvalidArgumentException('SubscriptionData requires a non-empty string id');
        }

        $customerId = isset($payload['customer_id']) && is_string($payload['customer_id']) ? $payload['customer_id'] : null;
        $originalCustomerId = isset($payload['original_customer_id']) && is_string($payload['original_customer_id'])
            ? $payload['original_customer_id']
            : null;
        $productId = isset($payload['product_id']) && is_string($payload['product_id']) ? $payload['product_id'] : null;

        $startsAtMs = self::parseMs($payload['starts_at'] ?? null);
        $currentPeriodStartsAtMs = self::parseMs($payload['current_period_starts_at'] ?? null);
        $currentPeriodEndsAtMs = self::parseMs($payload['current_period_ends_at'] ?? null);

        $givesAccess = array_key_exists('gives_access', $payload) ? self::parseBool($payload['gives_access']) : null;
        $pendingPayment = array_key_exists('pending_payment', $payload) ? self::parseBool($payload['pending_payment']) : null;

        $autoRenewalStatus = isset($payload['auto_renewal_status']) && is_string($payload['auto_renewal_status'])
            ? $payload['auto_renewal_status']
            : null;
        $status = isset($payload['status']) && is_string($payload['status']) ? $payload['status'] : null;

        $totalRevenueInUsd = isset($payload['total_revenue_in_usd']) && is_array($payload['total_revenue_in_usd'])
            ? $payload['total_revenue_in_usd']
            : null;

        $presentedOfferingId = isset($payload['presented_offering_id']) && is_string($payload['presented_offering_id'])
            ? $payload['presented_offering_id']
            : null;

        $entitlements = isset($payload['entitlements']) && is_array($payload['entitlements'])
            ? $payload['entitlements']
            : null;

        $environment = isset($payload['environment']) && is_string($payload['environment']) ? $payload['environment'] : null;
        $store = isset($payload['store']) && is_string($payload['store']) ? $payload['store'] : null;

        $storeSubscriptionIdentifier = null;
        if (array_key_exists('store_subscription_identifier', $payload)) {
            $ssi = $payload['store_subscription_identifier'];
            if (is_string($ssi)) {
                $storeSubscriptionIdentifier = $ssi;
            } elseif (is_int($ssi)) {
                $storeSubscriptionIdentifier = (string) $ssi;
            }
        }

        $ownership = isset($payload['ownership']) && is_string($payload['ownership']) ? $payload['ownership'] : null;

        $pendingChangesProduct = null;
        if (isset($payload['pending_changes']) && is_array($payload['pending_changes'])) {
            $pc = $payload['pending_changes'];
            if (isset($pc['product']) && is_array($pc['product'])) {
                $pendingChangesProduct = ProductData::fromArray($pc['product']);
            }
        }

        $country = isset($payload['country']) && is_string($payload['country']) ? $payload['country'] : null;
        $managementUrl = isset($payload['management_url']) && is_string($payload['management_url'])
            ? $payload['management_url']
            : null;

        return new self(
            $payload,
            $id,
            $customerId,
            $originalCustomerId,
            $productId,
            $startsAtMs,
            $currentPeriodStartsAtMs,
            $currentPeriodEndsAtMs,
            $givesAccess,
            $pendingPayment,
            $autoRenewalStatus,
            $status,
            $totalRevenueInUsd,
            $presentedOfferingId,
            $entitlements,
            $environment,
            $store,
            $storeSubscriptionIdentifier,
            $ownership,
            $pendingChangesProduct,
            $country,
            $managementUrl,
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

    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function getOriginalCustomerId(): ?string
    {
        return $this->originalCustomerId;
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }

    /** Milliseconds since epoch */
    public function getStartsAtMs(): ?int
    {
        return $this->startsAtMs;
    }

    public function getStartsAtDate(): ?\DateTimeImmutable
    {
        return self::dateFromMs($this->startsAtMs);
    }

    /** Milliseconds since epoch */
    public function getCurrentPeriodStartsAtMs(): ?int
    {
        return $this->currentPeriodStartsAtMs;
    }

    public function getCurrentPeriodStartsAtDate(): ?\DateTimeImmutable
    {
        return self::dateFromMs($this->currentPeriodStartsAtMs);
    }

    /** Milliseconds since epoch */
    public function getCurrentPeriodEndsAtMs(): ?int
    {
        return $this->currentPeriodEndsAtMs;
    }

    public function getCurrentPeriodEndsAtDate(): ?\DateTimeImmutable
    {
        return self::dateFromMs($this->currentPeriodEndsAtMs);
    }

    public function getGivesAccess(): ?bool
    {
        return $this->givesAccess;
    }

    public function getPendingPayment(): ?bool
    {
        return $this->pendingPayment;
    }

    public function getAutoRenewalStatus(): ?string
    {
        return $this->autoRenewalStatus;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getTotalRevenueInUsd(): ?array
    {
        return $this->totalRevenueInUsd;
    }

    public function getPresentedOfferingId(): ?string
    {
        return $this->presentedOfferingId;
    }

    /**
     * Entitlements list payload (object="list").
     *
     * @return array<string, mixed>|null
     */
    public function getEntitlements(): ?array
    {
        return $this->entitlements;
    }

    public function getEnvironment(): ?string
    {
        return $this->environment;
    }

    public function getStore(): ?string
    {
        return $this->store;
    }

    public function getStoreSubscriptionIdentifier(): ?string
    {
        return $this->storeSubscriptionIdentifier;
    }

    public function getOwnership(): ?string
    {
        return $this->ownership;
    }

    public function getPendingChangesProduct(): ?ProductData
    {
        return $this->pendingChangesProduct;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getManagementUrl(): ?string
    {
        return $this->managementUrl;
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
            'customer_id' => $this->customerId,
            'original_customer_id' => $this->originalCustomerId,
            'product_id' => $this->productId,
            'starts_at' => $this->startsAtMs,
            'current_period_starts_at' => $this->currentPeriodStartsAtMs,
            'current_period_ends_at' => $this->currentPeriodEndsAtMs,
            'gives_access' => $this->givesAccess,
            'pending_payment' => $this->pendingPayment,
            'auto_renewal_status' => $this->autoRenewalStatus,
            'status' => $this->status,
            'total_revenue_in_usd' => $this->totalRevenueInUsd,
            'presented_offering_id' => $this->presentedOfferingId,
            'entitlements' => $this->entitlements,
            'environment' => $this->environment,
            'store' => $this->store,
            'store_subscription_identifier' => $this->storeSubscriptionIdentifier,
            'ownership' => $this->ownership,
            'pending_changes' => $this->pendingChangesProduct ? ['product' => $this->pendingChangesProduct->toArray()] : null,
            'country' => $this->country,
            'management_url' => $this->managementUrl,
            'raw' => $this->raw,
        ];
    }

    private static function parseMs(mixed $value): ?int
    {
        if (is_int($value)) {
            return $value;
        }
        if (is_string($value) && is_numeric($value)) {
            return (int) $value;
        }

        return null;
    }

    private static function parseBool(mixed $value): ?bool
    {
        if (is_bool($value)) {
            return $value;
        }
        if (is_string($value)) {
            $lower = strtolower($value);
            if ($lower === 'true') {
                return true;
            }
            if ($lower === 'false') {
                return false;
            }
        }
        if (is_int($value)) {
            if ($value === 1) {
                return true;
            }
            if ($value === 0) {
                return false;
            }
        }

        return null;
    }

    private static function dateFromMs(?int $ms): ?\DateTimeImmutable
    {
        if ($ms === null) {
            return null;
        }

        return (new \DateTimeImmutable('@0'))
            ->setTimestamp((int) floor($ms / 1000))
            ->setTimezone(new \DateTimeZone('UTC'));
    }
}
