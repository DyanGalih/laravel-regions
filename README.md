# Laravel Indonesian Region API

[![Latest Version on Packagist](https://img.shields.io/badge/version-v1.0.0-blue?style=flat-square)](https://packagist.org/packages/dyangalih/laravel-regions)
[![Laravel Version](https://img.shields.io/badge/laravel-10%2F11%2F12%2F13-informational?style=flat-square&logo=laravel)](https://laravel.com)
[![License](https://img.shields.io/badge/license-MIT-brightgreen?style=flat-square)](https://packagist.org/packages/dyangalih/laravel-regions)
[![Status](https://img.shields.io/badge/status-stable-success?style=flat-square)](https://github.com/DyanGalih/laravel-regions)

A high-performance, governed Laravel package for Indonesian regional data (Provinces, Regencies, Districts, and Villages). Featuring a deep hierarchical REST API, global search capabilities, and optimized data ingestion.

## Requirements

- **PHP**: `^8.1` (to match composer.json)
- **Laravel**: `10.x`, `11.x`, `12.x`, or `13.x`

## Features

- **Full Indonesian Hierarchy**: Provinces → Regencies → Districts → Villages.
- **Dual-Access API**: Support for both deep hierarchical and flat global endpoints.
- **Deep Eager Loading**: Automatically includes full parent hierarchy without N+1 queries.
- **High-Performance Seeding**: Chunked data ingestion for high-volume datasets with automatic cache invalidation.
- **Intelligent Caching**: Permanent versioned caching for detail lookups with self-healing serialization recovery.
- **Zero-Config Auto-Discovery**: Works out of the box with standard Laravel commands.

## Engineering Standards

This package was developed using an advanced AI-assisted engineering workflow to ensure production-grade stability:
- **[Spec-Kit](https://github.com/github/spec-kit)**: Orchestrated development from specification to implementation.
- **[Memory-Hub](https://github.com/DyanGalih/spec-kit-memory-hub)**, **[Security-Review](https://github.com/DyanGalih/spec-kit-security-review)**, and **[Architecture-Guard](https://github.com/DyanGalih/spec-kit-architecture-guard)** enforced.

## Installation

```bash
composer require dyangalih/laravel-regions
```

## Quick Start (Zero-Config)

```bash
# 1. Run migrations
php artisan migrate

# 2. Seed the data
php artisan indonesia:seed
```

---

## API Documentation

All routes are prefixed with `/api/region`.

### Hierarchical Endpoints
Used for drill-down UIs or strictly scoped data retrieval.

| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `GET` | `/provinces` | List all provinces |
| `GET` | `/provinces/{id}` | Get province details |
| `GET` | `/provinces/{pId}/regencies` | List regencies in a province |
| `GET` | `/provinces/{pId}/regencies/{rId}` | Get regency details |
| `GET` | `/provinces/{pId}/regencies/{rId}/districts` | List districts in a regency |
| `GET` | `/provinces/{pId}/regencies/{rId}/districts/{dId}/villages` | List villages in a district |

### Global Search & Detail Endpoints
Used for global search boxes, autocomplete, or direct ID lookups.

| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `GET` | `/regencies` | Global search regencies |
| `GET` | `/regencies/{id}` | Get regency detail globally |
| `GET` | `/districts` | Global search districts |
| `GET` | `/districts/{id}` | Get district detail globally |
| `GET` | `/villages` | Global search villages |
| `GET` | `/villages/{id}` | Get village detail globally |

### Example JSON Response
When requesting a village (either globally or hierarchically), you get the full parent context:

```json
{
  "id": 1101010001,
  "districtId": 1101010,
  "name": "KEUDE BAKONGAN",
  "district": {
    "id": 1101010,
    "regencyId": 1101,
    "name": "BAKONGAN",
    "regency": {
      "id": 1101,
      "provinceId": 11,
      "name": "KABUPATEN ACEH SELATAN",
      "province": {
        "id": 11,
        "name": "ACEH"
      }
    }
  }
}
```

---

## Programmatic Usage

You can interact with the regional data directly in your PHP code using the Facade or Service.

### Using the Facade
The `Region` facade provides a clean, expressive syntax for data retrieval.

```php
use DyanGalih\LaravelRegion\Facades\Region;

// Search for a village globally
$villages = Region::searchVillages(query: 'Bakongan', limit: 10);

// Get a district with its full hierarchy
$district = Region::getDistrictById(1101010);

// Get regencies for a specific province
$regencies = Region::searchRegencies(provinceId: 11);
```

### Dependency Injection
You can also inject the `RegionService` into your controllers or services.

```php
use DyanGalih\LaravelRegion\Services\RegionService;

public function __construct(
    protected RegionService $regionService
) {}

public function getMyData()
{
    return $this->regionService->getVillageById(1101010001);
}
```

---

## Performance & Caching

The package implements an **Intelligent Caching** layer designed for static regional data.

### Forever Caching
All detail and ID-based lookups (`get*ById`, `get*Detail`) are cached **forever** by default. This ensures near-zero latency for repeated requests.

### Self-Healing Resilience
To prevent crashes in unstable serialization environments (e.g., PHP 8.4 drift), the package automatically detects and purges corrupted cache entries (like `__PHP_Incomplete_Class`), re-generating them on the fly.

### Cache Invalidation
The cache is versioned (`region.v{version}.*`). When you update your regional data, you must increment the version to invalidate all existing cache.

**Manual Flush:**
```php
use DyanGalih\LaravelRegion\Facades\Region;

Region::flushCache();
```

**Automatic Flush:**
The `php artisan indonesia:seed` command automatically triggers a cache flush upon successful completion.

---

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- **[Wilayah-Administrasi-Indonesia](https://github.com/guzfirdaus/Wilayah-Administrasi-Indonesia)**: The primary source for the Indonesian regional CSV data.
- **[Spec-Kit](https://github.com/github/spec-kit)**: The engineering framework used for governed development.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
