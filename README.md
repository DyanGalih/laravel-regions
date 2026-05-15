# Laravel Indonesian Region API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dyangalih/laravel-region.svg?style=flat-square)](https://packagist.org/packages/dyangalih/laravel-region)
[![Total Downloads](https://img.shields.io/packagist/dt/dyangalih/laravel-region.svg?style=flat-square)](https://packagist.org/packages/dyangalih/laravel-region)
[![License](https://img.shields.io/packagist/l/dyangalih/laravel-region.svg?style=flat-square)](https://packagist.org/packages/dyangalih/laravel-region)

A high-performance, governed Laravel package for Indonesian regional data (Provinces, Regencies, Districts, and Villages). Featuring a deep hierarchical REST API, robust request validation, and optimized data ingestion.

## Features

- **Full Indonesian Hierarchy**: Provinces → Regencies → Districts → Villages.
- **Deep RESTful API**: Intuitive hierarchical routing (e.g., `provinces/{id}/regencies`).
- **High-Performance Seeding**: Chunked data ingestion for high-volume datasets (Villages).
- **Zero-Config Auto-Discovery**: Works out of the box without publishing files.
- **Governed Architecture**: Strict `camelCase` DTOs and FormRequest validation.

## Engineering Standards

This package was developed using an advanced AI-assisted engineering workflow to ensure production-grade stability and security:

- **[Spec-Kit](https://github.com/DyanGalih/spec-kit)**: Orchestrated the entire development lifecycle from specification to implementation.
- **Memory-Hub**: Provided a durable project memory to maintain context and technical decisions across the development history.
- **Security-Review**: Enforced rigorous security audits during the implementation phase to mitigate common vulnerabilities.
- **Architecture-Guard**: Guaranteed that all code adheres to a strict [Architecture Constitution](.specify/memory/architecture_constitution.md), preventing technical debt and architectural drift.

## Installation

You can install the package via composer:

```bash
composer require dyangalih/laravel-region
```

## Quick Start (Zero-Config)

This package supports **Laravel Auto-Discovery**. After installation, you can immediately prepare your database and seed the regional data without publishing any configuration or migration files:

```bash
# 1. Run migrations directly from the package
php artisan migrate

# 2. Seed the regional data using the high-performance command
php artisan indonesia:seed
```

### Integration with `php artisan db:seed`

If you want to include the regional data as part of your standard application seeding process, add the following to your `database/seeders/DatabaseSeeder.php`:

```php
public function run(): void
{
    // Call the package's high-performance seeder
    $this->command->call('indonesia:seed');
}
```

## API Usage

All routes are prefixed with `/api/region`.

### Hierarchical Endpoints

| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `GET` | `/provinces` | List all provinces |
| `GET` | `/provinces/{id}` | Get province details |
| `GET` | `/provinces/{id}/regencies` | List regencies in a province |
| `GET` | `/provinces/{pId}/regencies/{rId}` | Get regency details |
| `GET` | `/provinces/{pId}/regencies/{rId}/districts` | List districts in a regency |
| `GET` | `/provinces/{pId}/regencies/{rId}/districts/{dId}/villages` | List villages in a district |
| `GET` | `/provinces/{pId}/regencies/{rId}/districts/{dId}/villages/{vId}` | Get village details |

### Global Search Endpoints

These endpoints allow searching across the entire Indonesian hierarchy without nested IDs.

| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `GET` | `/regencies` | Global search regencies |
| `GET` | `/regencies/{id}` | Get regency details globally |
| `GET` | `/districts` | Global search districts |
| `GET` | `/districts/{id}` | Get district details globally |
| `GET` | `/villages` | Global search villages |
| `GET` | `/villages/{id}` | Get village details globally |

### Query Parameters

All list endpoints support:
- `q`: Search by name.
- `limit`: Pagination limit (default: 15, max: 100).

## Optional Customization

If you need to override the default behavior, publish the assets:

```bash
# Publish migrations for customization
php artisan vendor:publish --tag="region-migrations"

# Publish config (prefix, middleware, table names)
php artisan vendor:publish --tag="region-config"
```

## Development

```bash
composer install
composer test
composer lint
```

## Security

If you discover any security-related issues, please email dyan.galih@gmail.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
