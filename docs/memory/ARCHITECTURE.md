# Architecture: laravel-region

## System Shape
- **Architecture Style**: Monolith Laravel Library
- **Framework**: Laravel 10.x / 11.x
- **Key Dependencies**: `spatie/laravel-data`, `spatie/package-skeleton-laravel`

## Layer Boundaries
- **Entry Layer (HTTP/CLI/Facade)**:
    - Controllers: Handle routing and response delegation.
    - Facade: Static interface for `RegionService`.
    - Commands: For seeding and manual data management.
- **Service Layer**: 
    - The primary home for business logic, filtering, and data retrieval.
    - MUST return `Data` objects from `spatie/laravel-data`.
- **Domain/Data Layer**:
    - Eloquent Models for Provinces, Regencies, Districts, and Villages.
    - Standard Laravel Migrations and Seeders.

## Trust Boundaries
- **Consumer DB**: The package uses the consumer's default database connection.
- **API Surface**: Read-only by default. No public CUD endpoints.

## Tech Stack
- **PHP**: 8.1+
- **Laravel**: 10.0+
- **Contracts**: `spatie/laravel-data`
- **Testing**: PHPUnit / Pest

## Data Model (Summary)
- **Province**: Top-level entity.
- **Regency**: Child of Province.
- **District**: Child of Regency.
- **Village**: Child of District.
