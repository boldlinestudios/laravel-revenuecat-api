<?php

use BoldlineStudios\RevenueCatApi\Exceptions\ApiResponseException;
use BoldlineStudios\RevenueCatApi\Exceptions\AuthenticationException;
use BoldlineStudios\RevenueCatApi\Exceptions\AuthorizationException;
use BoldlineStudios\RevenueCatApi\Exceptions\BadRequestException;
use BoldlineStudios\RevenueCatApi\Exceptions\ConflictException;
use BoldlineStudios\RevenueCatApi\Exceptions\NotFoundException;
use BoldlineStudios\RevenueCatApi\Exceptions\RateLimitException;
use BoldlineStudios\RevenueCatApi\Exceptions\ServerErrorException;
use BoldlineStudios\RevenueCatApi\Exceptions\ValidationException;
use BoldlineStudios\RevenueCatApi\Facades\RevenueCat;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    // Override config for example domain to avoid hitting real endpoints
    config([
        'revenuecat-api.api_key' => 'test_api_key',
        'revenuecat-api.base_url' => 'https://api.example.com/v2',
        'revenuecat-api.project_id' => 'test_project',
        'revenuecat-api.timeout' => 30,
    ]);
});

describe('Rate Limit Exceptions', function () {
    test('throws RateLimitException on 429 response with headers', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps' => Http::response([
                'object' => 'error',
                'type' => 'rate_limit_error',
                'message' => 'Rate limit exceeded',
                'retryable' => true,
                'doc_url' => 'https://errors.rev.cat/rate-limit-error',
            ], 429, [
                'X-RateLimit-Limit' => '100',
                'X-RateLimit-Remaining' => '0',
                'X-RateLimit-Reset' => '1640995200',
            ]),
        ]);

        expect(fn () => RevenueCat::apps()->list())
            ->toThrow(RateLimitException::class, 'Rate limit exceeded');

        try {
            RevenueCat::apps()->list();
        } catch (RateLimitException $e) {
            expect($e->getStatusCode())->toBe(429);
            expect($e->getErrorCode())->toBeNull(); // RevenueCat doesn't use 'code' field
            expect($e->getErrorType())->toBe('rate_limit_error');
            expect($e->getLimit())->toBe(100);
            expect($e->getRemaining())->toBe(0);
            expect($e->getReset())->toBe(1640995200);
            expect($e->getDetails())->toHaveKey('object');
            expect($e->getDetails())->toHaveKey('retryable');
            expect($e->getDetails())->toHaveKey('doc_url');
        }
    });

    test('throws RateLimitException on 429 with missing headers gracefully', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps' => Http::response([
                'object' => 'error',
                'type' => 'rate_limit_error',
                'message' => 'Rate limit exceeded',
                'retryable' => true,
                'doc_url' => 'https://errors.rev.cat/rate-limit-error',
            ], 429),
        ]);

        expect(fn () => RevenueCat::apps()->list())
            ->toThrow(RateLimitException::class);

        try {
            RevenueCat::apps()->list();
        } catch (RateLimitException $e) {
            expect($e->getLimit())->toBeNull();
            expect($e->getRemaining())->toBeNull();
            expect($e->getReset())->toBeNull();
        }
    });
});

describe('Client Error Exceptions', function () {
    test('throws BadRequestException on 400 response', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps' => Http::response([
                'object' => 'error',
                'type' => 'invalid_request',
                'message' => 'Content-Type not application/json',
                'retryable' => false,
                'doc_url' => 'https://errors.rev.cat/invalid-request',
            ], 400),
        ]);

        expect(fn () => RevenueCat::apps()->list())
            ->toThrow(BadRequestException::class, 'Content-Type not application/json');

        try {
            RevenueCat::apps()->list();
        } catch (BadRequestException $e) {
            expect($e->getStatusCode())->toBe(400);
            expect($e->getErrorCode())->toBeNull(); // RevenueCat doesn't use 'code' field
            expect($e->getErrorType())->toBe('invalid_request');
            expect($e->getDetails())->toHaveKey('object');
            expect($e->getDetails())->toHaveKey('retryable');
            expect($e->getDetails())->toHaveKey('doc_url');
        }
    });

    test('throws AuthenticationException on 401 response', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps' => Http::response([
                'object' => 'error',
                'type' => 'authentication_error',
                'message' => 'Invalid API key',
                'retryable' => false,
                'doc_url' => 'https://errors.rev.cat/authentication-error',
            ], 401),
        ]);

        expect(fn () => RevenueCat::apps()->list())
            ->toThrow(AuthenticationException::class, 'Invalid API key');

        try {
            RevenueCat::apps()->list();
        } catch (AuthenticationException $e) {
            expect($e->getStatusCode())->toBe(401);
            expect($e->getErrorCode())->toBeNull(); // RevenueCat doesn't use 'code' field
            expect($e->getErrorType())->toBe('authentication_error');
            expect($e->getDetails())->toHaveKey('object');
            expect($e->getDetails())->toHaveKey('retryable');
            expect($e->getDetails())->toHaveKey('doc_url');
        }
    });

    test('throws AuthorizationException on 403 response', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps' => Http::response([
                'object' => 'error',
                'type' => 'authorization_error',
                'message' => 'Insufficient permissions',
                'retryable' => false,
                'doc_url' => 'https://errors.rev.cat/authorization-error',
            ], 403),
        ]);

        expect(fn () => RevenueCat::apps()->list())
            ->toThrow(AuthorizationException::class, 'Insufficient permissions');

        try {
            RevenueCat::apps()->list();
        } catch (AuthorizationException $e) {
            expect($e->getStatusCode())->toBe(403);
            expect($e->getErrorCode())->toBeNull(); // RevenueCat doesn't use 'code' field
            expect($e->getErrorType())->toBe('authorization_error');
            expect($e->getDetails())->toHaveKey('object');
            expect($e->getDetails())->toHaveKey('retryable');
            expect($e->getDetails())->toHaveKey('doc_url');
        }
    });

    test('throws NotFoundException on 404 response', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps/invalid-id' => Http::response([
                'object' => 'error',
                'type' => 'resource_missing',
                'message' => 'Resource not found',
                'retryable' => false,
                'doc_url' => 'https://errors.rev.cat/resource-missing',
            ], 404),
        ]);

        expect(fn () => RevenueCat::apps()->get('invalid-id'))
            ->toThrow(NotFoundException::class, 'Resource not found');

        try {
            RevenueCat::apps()->get('invalid-id');
        } catch (NotFoundException $e) {
            expect($e->getStatusCode())->toBe(404);
            expect($e->getErrorCode())->toBeNull(); // RevenueCat doesn't use 'code' field
            expect($e->getErrorType())->toBe('resource_missing');
            expect($e->getDetails())->toHaveKey('object');
            expect($e->getDetails())->toHaveKey('retryable');
            expect($e->getDetails())->toHaveKey('doc_url');
        }
    });

    test('throws ConflictException on 409 response', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps' => Http::response([
                'object' => 'error',
                'type' => 'resource_exists',
                'message' => 'Resource already exists',
                'retryable' => false,
                'doc_url' => 'https://errors.rev.cat/resource-exists',
            ], 409),
        ]);

        expect(fn () => RevenueCat::apps()->create(['name' => 'Duplicate App']))
            ->toThrow(ConflictException::class, 'Resource already exists');

        try {
            RevenueCat::apps()->create(['name' => 'Duplicate App']);
        } catch (ConflictException $e) {
            expect($e->getStatusCode())->toBe(409);
            expect($e->getErrorCode())->toBeNull(); // RevenueCat doesn't use 'code' field
            expect($e->getErrorType())->toBe('resource_exists');
            expect($e->getDetails())->toHaveKey('object');
            expect($e->getDetails())->toHaveKey('retryable');
            expect($e->getDetails())->toHaveKey('doc_url');
        }
    });

    test('throws ValidationException on 422 response', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps' => Http::response([
                'object' => 'error',
                'type' => 'parameter_error',
                'param' => 'customer_id',
                'message' => 'id is too long',
                'retryable' => false,
                'doc_url' => 'https://errors.rev.cat/parameter-error',
            ], 422),
        ]);

        expect(fn () => RevenueCat::apps()->create([]))
            ->toThrow(ValidationException::class, 'id is too long');

        try {
            RevenueCat::apps()->create([]);
        } catch (ValidationException $e) {
            expect($e->getStatusCode())->toBe(422);
            expect($e->getErrorCode())->toBeNull(); // RevenueCat doesn't use 'code' field
            expect($e->getErrorType())->toBe('parameter_error');
            expect($e->getDetails())->toHaveKey('object');
            expect($e->getDetails())->toHaveKey('param');
            expect($e->getDetails())->toHaveKey('retryable');
            expect($e->getDetails())->toHaveKey('doc_url');
        }
    });
});

describe('Server Error Exceptions', function () {
    test('throws ServerErrorException on 500 response', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps' => Http::response([
                'object' => 'error',
                'type' => 'server_error',
                'message' => 'There was an internal server error',
                'retryable' => true,
                'doc_url' => 'https://errors.rev.cat/server-error',
                'backoff_ms' => 1000,
            ], 500),
        ]);

        expect(fn () => RevenueCat::apps()->list())
            ->toThrow(ServerErrorException::class, 'There was an internal server error');

        try {
            RevenueCat::apps()->list();
        } catch (ServerErrorException $e) {
            expect($e->getStatusCode())->toBe(500);
            expect($e->getErrorCode())->toBeNull(); // RevenueCat doesn't use 'code' field
            expect($e->getErrorType())->toBe('server_error');
            expect($e->getDetails())->toHaveKey('object');
            expect($e->getDetails())->toHaveKey('retryable');
            expect($e->getDetails())->toHaveKey('doc_url');
            expect($e->getDetails())->toHaveKey('backoff_ms');
        }
    });

    test('throws ServerErrorException on 502 response', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps' => Http::response([
                'object' => 'error',
                'type' => 'server_error',
                'message' => 'Bad gateway',
                'retryable' => true,
                'doc_url' => 'https://errors.rev.cat/server-error',
            ], 502),
        ]);

        expect(fn () => RevenueCat::apps()->list())
            ->toThrow(ServerErrorException::class, 'Bad gateway');
    });

    test('throws ServerErrorException on 503 response', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps' => Http::response([
                'object' => 'error',
                'type' => 'server_error',
                'message' => 'Service unavailable',
                'retryable' => true,
                'doc_url' => 'https://errors.rev.cat/server-error',
            ], 503),
        ]);

        expect(fn () => RevenueCat::apps()->list())
            ->toThrow(ServerErrorException::class, 'Service unavailable');
    });
});

describe('Error Response Parsing', function () {
    test('handles malformed error response gracefully', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps' => Http::response([
                'unexpected' => 'structure',
            ], 400),
        ]);

        expect(fn () => RevenueCat::apps()->list())
            ->toThrow(BadRequestException::class, 'RevenueCat API error');

        try {
            RevenueCat::apps()->list();
        } catch (BadRequestException $e) {
            expect($e->getErrorCode())->toBeNull();
            expect($e->getErrorType())->toBeNull();
            expect($e->getDetails())->toHaveKey('unexpected');
        }
    });

    test('handles non-array response gracefully', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps' => Http::response('Invalid JSON', 400),
        ]);

        expect(fn () => RevenueCat::apps()->list())
            ->toThrow(BadRequestException::class, 'RevenueCat API error');
    });

    test('handles empty response gracefully', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps' => Http::response('', 400),
        ]);

        expect(fn () => RevenueCat::apps()->list())
            ->toThrow(BadRequestException::class, 'RevenueCat API error');
    });
});

describe('Unknown Status Code Handling', function () {
    test('throws generic ApiResponseException for unknown status codes', function () {
        Http::fake([
            'https://api.example.com/v2/projects/test_project/apps' => Http::response([
                'object' => 'error',
                'type' => 'unknown_error',
                'message' => 'Unknown error occurred',
                'retryable' => false,
                'doc_url' => 'https://errors.rev.cat/unknown-error',
            ], 418), // I'm a teapot - definitely unknown
        ]);

        expect(fn () => RevenueCat::apps()->list())
            ->toThrow(ApiResponseException::class, 'Unknown error occurred');

        try {
            RevenueCat::apps()->list();
        } catch (ApiResponseException $e) {
            expect($e->getStatusCode())->toBe(418);
            expect($e->getErrorCode())->toBeNull(); // RevenueCat doesn't use 'code' field
            expect($e->getErrorType())->toBe('unknown_error');
        }
    });
});

describe('Exception Inheritance', function () {
    test('all exceptions extend RevenueCatException', function () {
        $exceptions = [
            BadRequestException::class,
            AuthenticationException::class,
            AuthorizationException::class,
            NotFoundException::class,
            ConflictException::class,
            ValidationException::class,
            RateLimitException::class,
            ServerErrorException::class,
        ];

        foreach ($exceptions as $exceptionClass) {
            $exception = new $exceptionClass('Test message', 400);
            expect($exception)->toBeInstanceOf(\BoldlineStudios\RevenueCatApi\Exceptions\RevenueCatException::class);
        }
    });

    test('all exceptions extend ApiResponseException except base', function () {
        $exceptions = [
            BadRequestException::class,
            AuthenticationException::class,
            AuthorizationException::class,
            NotFoundException::class,
            ConflictException::class,
            ValidationException::class,
            RateLimitException::class,
            ServerErrorException::class,
        ];

        foreach ($exceptions as $exceptionClass) {
            $exception = new $exceptionClass('Test message', 400);
            expect($exception)->toBeInstanceOf(\BoldlineStudios\RevenueCatApi\Exceptions\ApiResponseException::class);
        }
    });
});
