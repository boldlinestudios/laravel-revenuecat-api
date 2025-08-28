<?php

namespace BoldlineStudios\RevenueCatApi\Data\Support;

final class Payload
{
    /**
     * Require a non-empty string field from a payload.
     *
     * @param  array<string, mixed>  $data
     */
    public static function requireNonEmptyString(array $data, string $key, string $context): string
    {
        $value = $data[$key] ?? null;
        if (! is_string($value) || trim($value) === '') {
            throw new \InvalidArgumentException($context.' requires a non-empty string '.$key);
        }

        return $value;
    }

    /**
     * Read an optional string field.
     *
     * @param  array<string, mixed>  $data
     */
    public static function optionalString(array $data, string $key): ?string
    {
        $value = $data[$key] ?? null;

        return is_string($value) ? $value : null;
    }

    /**
     * Parse milliseconds since epoch from mixed value (int|string), else null.
     */
    public static function parseMs(mixed $value): ?int
    {
        if (is_int($value)) {
            return $value;
        }
        if (is_string($value) && is_numeric($value)) {
            return (int) $value;
        }

        return null;
    }

    /**
     * Parse boolean from mixed (bool|string/int common representations), else null.
     */
    public static function parseBool(mixed $value): ?bool
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

    /**
     * Convert milliseconds since epoch to UTC DateTimeImmutable.
     */
    public static function dateFromMs(?int $ms): ?\DateTimeImmutable
    {
        if ($ms === null) {
            return null;
        }

        return (new \DateTimeImmutable('@0'))
            ->setTimestamp((int) floor($ms / 1000))
            ->setTimezone(new \DateTimeZone('UTC'));
    }
}
