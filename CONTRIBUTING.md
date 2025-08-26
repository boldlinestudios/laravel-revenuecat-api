# Contributing to Laravel RevenueCat API

Thanks for taking the time to contribute!

This project follows Laravel’s general conventions and aims for a clean, typed API with great docs and tests.

## Code of Conduct
We adhere to the Laravel community Code of Conduct. Be kind, constructive, and professional.

It's the duty of the maintainer to ensure that all submissions to the project are of sufficient quality to benefit the project.

## How We Work
- Bug reports are welcome, but we strongly encourage pull requests where possible.
- Small fixes/docs improvements? **PRs welcome**
- Keep PRs focused: **one change per PR**.

## Branching & Commit Style
- Branch names:  
  - `feat/short-description`  
  - `fix/short-description`
- We recommend following Conventional Commits (https://www.conventionalcommits.org/en/v1.0.0/) for clarity: 
  - `feat: add ProductData DTO`  
  - `fix: handle 429 reset header`  
  - `docs: clarify customer example`

## Coding Standards
We use Laravel Pint and strict static analysis.

```bash
./vendor/bin/pint
./vendor/bin/phpstan analyze
```

- Keep public APIs typed. Prefer DTOs over raw arrays in returns. Use precise PHPDoc array-shapes when needed.
- Follow Laravel naming and structure conventions (`Facades`, `ServiceProvider`, `Http`, `Endpoints`, `Data`).

## Tests (Required)
All features and fixes must include tests (Pest v3)

```bash
./vendor/bin/pest
```

## Documentation
If your change affects behavior, update the docs:
- README.md — short, onboarding examples.
- docs/ENDPOINTS.md — full endpoint reference and larger examples.

## PR Checklist
- Added/updated tests
- ./vendor/bin/pint passes
- ./vendor/bin/phpstan analyze passes
- ./vendor/bin/pest passes
- Docs updated (README/ENDPOINTS) if behavior changed
- Linked related issue(s), and added notes/screenshots if helpful

## Versioning & Releases
- Maintainers handle tagging and CHANGELOG/release notes.
- Please avoid modifying version numbers in `composer.json` unless requested.

## Security
If you discover a security vulnerability, please do not open a public issue. Email the maintainers directly at security@boldlinestudios.com so we can coordinate a fix.

## Thanks
Your contributions make this package better for everyone. We appreciate your time and care!
