# Changelog

All notable changes to `laravel-revenuecat-api` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Add customer entitlement grant endpoint (`POST /customers/{customer_id}/actions/grant_entitlement`)

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

[Unreleased]: https://github.com/boldline-studios/laravel-revenuecat-api/compare/0.1.0...HEAD
[0.1.0]: https://github.com/boldline-studios/laravel-revenuecat-api/releases/tag/0.1.0
