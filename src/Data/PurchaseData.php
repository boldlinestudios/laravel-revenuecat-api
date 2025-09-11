<?php

namespace BoldLineStudios\RevenueCatApi\Data;

use BoldLineStudios\RevenueCatApi\Data\Support\Payload;
use Illuminate\Http\Client\Response;

class PurchaseData
{
    /**
     * @param  array<string, mixed>  $raw
     * @param  array<string, mixed>|null  $revenueInUsd
     * @param  array<string, mixed>|null  $entitlements
     */
    private function __construct(
        /** @var array<string, mixed> */
        private array $raw,
        private string $resourceType,
        private string $id,
        private ?string $customerId,
        private ?string $originalCustomerId,
        private ?string $productId,
        /** Milliseconds since epoch, if provided by the API */
        private ?int $purchasedAtMs,
        /** @var array<string, mixed>|null */
        private ?array $revenueInUsd,
        private ?int $quantity,
        private ?string $status,
        private ?string $presentedOfferingId,
        /** @var array<EntitlementData>|null */
        private ?array $entitlements,
        private ?string $environment,
        private ?string $store,
        /** Often numeric or string depending on store; normalize to string when possible */
        private ?string $storePurchaseIdentifier,
        private ?string $ownership,
        private ?string $country,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $resourceType = Payload::requireNonEmptyString($payload, 'object', 'PurchaseData');
        $id = Payload::requireNonEmptyString($payload, 'id', 'PurchaseData');

        $customerId = isset($payload['customer_id']) && is_string($payload['customer_id'])
            ? $payload['customer_id']
            : null;

        $originalCustomerId = isset($payload['original_customer_id']) && is_string($payload['original_customer_id'])
            ? $payload['original_customer_id']
            : null;

        $productId = isset($payload['product_id']) && is_string($payload['product_id'])
            ? $payload['product_id']
            : null;

        $purchasedAtMs = Payload::parseMs($payload['purchased_at'] ?? null);

        $revenueInUsd = isset($payload['revenue_in_usd']) && is_array($payload['revenue_in_usd'])
            ? $payload['revenue_in_usd']
            : null;

        $quantity = null;
        if (array_key_exists('quantity', $payload)) {
            $q = $payload['quantity'];
            if (is_int($q)) {
                $quantity = $q;
            } elseif (is_string($q) && is_numeric($q)) {
                $quantity = (int) $q;
            }
        }

        $status = isset($payload['status']) && is_string($payload['status']) ? $payload['status'] : null;

        $presentedOfferingId = isset($payload['presented_offering_id']) && is_string($payload['presented_offering_id'])
            ? $payload['presented_offering_id']
            : null;

        $entitlements = null;
        if (isset($payload['entitlements']) && is_array($payload['entitlements'])) {
            $items = $payload['entitlements']['items'] ?? [];
            $items = is_array($items) ? $items : [];

            $mapped = [];
            foreach ($items as $item) {
                if (is_array($item)) {
                    /** @var array<string, mixed> $item */
                    $mapped[] = EntitlementData::fromArray($item);
                }
            }

            $entitlements = $mapped;
        }

        $environment = isset($payload['environment']) && is_string($payload['environment']) ? $payload['environment'] : null;

        $store = isset($payload['store']) && is_string($payload['store']) ? $payload['store'] : null;

        $storePurchaseIdentifier = null;
        if (array_key_exists('store_purchase_identifier', $payload)) {
            $spi = $payload['store_purchase_identifier'];
            if (is_string($spi)) {
                $storePurchaseIdentifier = $spi;
            } elseif (is_int($spi)) {
                $storePurchaseIdentifier = (string) $spi;
            }
        }

        $ownership = isset($payload['ownership']) && is_string($payload['ownership']) ? $payload['ownership'] : null;

        $country = isset($payload['country']) && is_string($payload['country']) ? $payload['country'] : null;

        return new self(
            $payload,
            $resourceType,
            $id,
            $customerId,
            $originalCustomerId,
            $productId,
            $purchasedAtMs,
            $revenueInUsd,
            $quantity,
            $status,
            $presentedOfferingId,
            $entitlements,
            $environment,
            $store,
            $storePurchaseIdentifier,
            $ownership,
            $country,
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

    public function getResourceType(): string
    {
        return $this->resourceType;
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
    public function getPurchasedAtMs(): ?int
    {
        return $this->purchasedAtMs;
    }

    public function getPurchasedAtDate(): ?\DateTimeImmutable
    {
        return Payload::dateFromMs($this->purchasedAtMs);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRevenueInUsd(): ?array
    {
        return $this->revenueInUsd;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function getStatus(): ?string
    {
        return $this->status;
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

    public function getStorePurchaseIdentifier(): ?string
    {
        return $this->storePurchaseIdentifier;
    }

    public function getOwnership(): ?string
    {
        return $this->ownership;
    }

    public function getCountry(): ?string
    {
        return $this->country;
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
            'id' => $this->id,
            'customer_id' => $this->customerId,
            'original_customer_id' => $this->originalCustomerId,
            'product_id' => $this->productId,
            'purchased_at' => $this->purchasedAtMs,
            'revenue_in_usd' => $this->revenueInUsd,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'presented_offering_id' => $this->presentedOfferingId,
            'entitlements' => $this->entitlements,
            'environment' => $this->environment,
            'store' => $this->store,
            'store_purchase_identifier' => $this->storePurchaseIdentifier,
            'ownership' => $this->ownership,
            'country' => $this->country,
            'raw' => $this->raw,
        ];
    }
}
