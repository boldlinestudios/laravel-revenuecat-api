<?php

namespace BoldlineStudios\RevenueCatApi\Data\Customer;

use BoldlineStudios\RevenueCatApi\Data\Support\Payload;
use Illuminate\Http\Client\Response;

class VirtualCurrencyBalanceData
{
    /**
     * @param  array<string, mixed>  $raw
     */
    private function __construct(
        /** @var array<string, mixed> */
        private array $raw,
        private string $currencyCode,
        private int $balance,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $currencyCode = Payload::requireNonEmptyString($payload, 'currency_code', 'VirtualCurrencyBalanceData');
        $balance = Payload::requireInteger($payload, 'balance', 'VirtualCurrencyBalanceData');

        return new self($payload, $currencyCode, $balance);
    }

    public static function fromResponse(Response $response): self
    {
        $payload = $response->json();
        $payload = is_array($payload) ? $payload : [];

        /** @var array<string, mixed> $payload */
        return self::fromArray($payload);
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function getBalance(): int
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
            'currency_code' => $this->currencyCode,
            'balance' => $this->balance,
            'raw' => $this->raw,
        ];
    }
}
