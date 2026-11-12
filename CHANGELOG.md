# Changelog

All notable changes to `laravel-revenuecat-api` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.3] - 2026-11-12

### Added
- Laravel 13 support (`illuminate/support` `^11.0 || ^12.0 || ^13.0`)

## [0.1.2] - 2025-12-12

### Added
- Add customer entitlement grant endpoint (`POST /customers/{customer_id}/actions/grant_entitlement`)

## [0.1.1] - 2025-09-12

### Fixed
- `REVENUECAT_TIMEOUT` values from `.env` are cast to `int`

## [0.1.0] - 2025-09-11

### Added
- Unofficial Laravel integration for RevenueCat API v2
- Support for RevenueCat resources: Apps, Customers, Subscriptions, Products, Purchases, Invoices, Offerings, Packages, Paywalls, Entitlements
- Type-safe DTOs for API responses
- Laravel Facade (`RevenueCat`)
- Error handling with custom exceptions
- Pagination support for list operations
- Both endpoint-style and convenience-style method calls
- Documentation
- Test suite using PestPHP v3

---

[Unreleased]: https://github.com/boldlinestudios/laravel-revenuecat-api/compare/v0.1.3...HEAD
[0.1.3]: https://github.com/boldlinestudios/laravel-revenuecat-api/releases/tag/v0.1.3
[0.1.2]: https://github.com/boldlinestudios/laravel-revenuecat-api/releases/tag/v0.1.2
[0.1.1]: https://github.com/boldlinestudios/laravel-revenuecat-api/releases/tag/v0.1.1
[0.1.0]: https://github.com/boldlinestudios/laravel-revenuecat-api/releases/tag/v0.1.0
