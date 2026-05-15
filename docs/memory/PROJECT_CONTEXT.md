# Project Context: laravel-region

## System Purpose
A ready-to-use Laravel package providing Indonesian regional data (Provinces, Regencies, Districts, Villages) with built-in migrations, seeders, and read-only API endpoints.

## Core Goals
- Provide accurate Indonesian regional data.
- Offer an intuitive developer experience via Facades and Services.
- Ensure high performance for large datasets (e.g., 80k+ villages).
- Maintain strict compatibility with Laravel 10+ and PHP 8.1+.

## User Personas
- **Laravel Developers**: Integrating regional data into their applications for address management, shipping, or reporting.

## Key Workflows
- **Seeding**: Populating the consumer's database with high-volume regional data.
- **Data Retrieval**: Accessing regions via Facade, Service, or built-in API.
- **Relationship Navigation**: Querying parent-child relationships (e.g., Province -> Regencies).

## Success Criteria
- Seamless installation via Composer.
- Successful seeding of all 80k+ records without memory issues.
- Stable API response contracts via `laravel-data`.
