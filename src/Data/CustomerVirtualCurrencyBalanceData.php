<?php

namespace BoldlineStudios\RevenueCatApi\Data;

use BoldlineStudios\RevenueCatApi\Data\Support\Payload;
use Illuminate\Http\Client\Response;

class CustomerVirtualCurrencyBalanceData
{
    /**
     * @param  array<string, mixed>  $raw
     */
    private function __construct(
        /** @var array<string, mixed> */
        private array $raw,
        private string $id,
        private string $currency,
        private ?int $balance,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $id = Payload::requireNonEmptyString($payload, 'id', 'CustomerVirtualCurrencyBalanceData');
        $currency = Payload::requireNonEmptyString($payload, 'currency', 'CustomerVirtualCurrencyBalanceData');
        $balanceRaw = $payload['balance'] ?? null;
        $balance = is_int($balanceRaw) ? $balanceRaw : (is_numeric($balanceRaw) ? (int) $balanceRaw : null);

        return new self($payload, $id, $currency, $balance);
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

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getBalance(): ?int
    {
        return $this->balance;
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
            'currency' => $this->currency,
            'balance' => $this->balance,
            'raw' => $this->raw,
        ];
    }
}
