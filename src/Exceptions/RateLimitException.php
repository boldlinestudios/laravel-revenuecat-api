<?php

namespace BoldlineStudios\RevenueCatApi\Exceptions;

class RateLimitException extends ApiResponseException
{
    public function __construct(
        string $message,
        int $statusCode,
        private ?int $limit = null,
        private ?int $remaining = null,
        private ?int $reset = null,
        ?string $errorCode = null,
        ?string $errorType = null,
        ?string $docsUrl = null,
        ?array $details = null,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $statusCode, $errorCode, $errorType, $docsUrl, $details, $previous);
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getRemaining(): ?int
    {
        return $this->remaining;
    }

    public function getReset(): ?int
    {
        return $this->reset;
    }
}
