<?php

use BoldLineStudios\RevenueCatApi\Data\Support\Payload;

// requireNonEmptyString
test('requireNonEmptyString returns the string when valid', function () {
    $val = Payload::requireNonEmptyString(['id' => 'abc'], 'id', 'Ctx');
    expect($val)->toBe('abc');
});

test('requireNonEmptyString trims and rejects empty/whitespace', function () {
    expect(fn () => Payload::requireNonEmptyString(['id' => '   '], 'id', 'Ctx'))
        ->toThrow(InvalidArgumentException::class);
});

test('requireNonEmptyString throws on missing or non-string', function () {
    expect(fn () => Payload::requireNonEmptyString([], 'id', 'Ctx'))
        ->toThrow(InvalidArgumentException::class);
    expect(fn () => Payload::requireNonEmptyString(['id' => 123], 'id', 'Ctx'))
        ->toThrow(InvalidArgumentException::class);
});

// optionalString
test('optionalString returns string or null', function () {
    expect(Payload::optionalString(['k' => 'v'], 'k'))->toBe('v');
    expect(Payload::optionalString([], 'k'))->toBeNull();
    expect(Payload::optionalString(['k' => 10], 'k'))->toBeNull();
});

// parseMs
test('parseMs parses int and numeric string', function () {
    expect(Payload::parseMs(1700000000))->toBe(1700000000);
    expect(Payload::parseMs('1700000000'))->toBe(1700000000);
});

test('parseMs returns null for non-numeric', function () {
    expect(Payload::parseMs('abc'))->toBeNull();
    expect(Payload::parseMs(null))->toBeNull();
});

// parseBool
test('parseBool parses booleans', function () {
    expect(Payload::parseBool(true))->toBeTrue();
    expect(Payload::parseBool(false))->toBeFalse();
});

test('parseBool parses common string/int representations', function () {
    expect(Payload::parseBool('true'))->toBeTrue();
    expect(Payload::parseBool('false'))->toBeFalse();
    expect(Payload::parseBool('TRUE'))->toBeTrue();
    expect(Payload::parseBool('FALSE'))->toBeFalse();
    expect(Payload::parseBool(1))->toBeTrue();
    expect(Payload::parseBool(0))->toBeFalse();
});

test('parseBool returns null for unknown values', function () {
    expect(Payload::parseBool('yes'))->toBeNull();
    expect(Payload::parseBool('no'))->toBeNull();
    expect(Payload::parseBool(2))->toBeNull();
    expect(Payload::parseBool(null))->toBeNull();
});

test('dateFromMs converts milliseconds to UTC DateTimeImmutable', function () {
    $dt = Payload::dateFromMs(1000);
    expect($dt)->toBeInstanceOf(DateTimeImmutable::class);
    expect($dt->getTimestamp())->toBe(1);
    expect($dt->getTimezone()->getName())->toBe('UTC');
});

test('dateFromMs returns null when input is null', function () {
    expect(Payload::dateFromMs(null))->toBeNull();
});
