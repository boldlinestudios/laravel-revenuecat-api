<?php

namespace BoldLineStudios\RevenueCatApi\Exceptions;

class RateLimitException extends ApiResponseException
{
    /**
     * Create a specialized exception for HTTP 429 rate limiting.
     *
     * @param  string  $message  Human-readable error message
     * @param  int  $statusCode  HTTP status code (typically 429)
     * @param  int|null  $limit  Maximum number of requests allowed in the current window
     * @param  int|null  $remaining  Number of requests remaining in the current window
     * @param  int|null  $reset  Unix timestamp (seconds) when the rate limit resets
     * @param  string|null  $errorCode  Provider-specific error code, if present
     * @param  string|null  $errorType  Provider-specific error type, if present
     * @param  string|null  $docsUrl  URL to provider documentation for this error, if available
     * @param  array<string, mixed>|null  $details  Additional structured error details payload
     * @param  \Throwable|null  $previous  Previous exception for chaining
     */
    public function __construct(
        string $message,
        int $statusCode,
        private ?int $limit = null,
        private ?int $remaining = null,
        private ?int $reset = null,
        ?string $errorCode = null,
        ?string $errorType = null,
        ?string $docsUrl = null,
        /** @var array<string, mixed>|null */
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
