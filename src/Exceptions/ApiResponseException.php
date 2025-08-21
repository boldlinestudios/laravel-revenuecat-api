<?php

namespace BoldlineStudios\RevenueCatApi\Exceptions;

class ApiResponseException extends RevenueCatException
{
    public function __construct(
        string $message,
        private int $statusCode,
        private ?string $errorCode = null,
        private ?string $errorType = null,
        private ?string $docsUrl = null,
        /** @var array<string, mixed>|null */
        private ?array $details = null,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function getErrorType(): ?string
    {
        return $this->errorType;
    }

    public function getDocsUrl(): ?string
    {
        return $this->docsUrl;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getDetails(): ?array
    {
        return $this->details;
    }
}
