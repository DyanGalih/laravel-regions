# Laravel Indonesian Region API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dyangalih/laravel-region.svg?style=flat-square)](https://pacakgist.org/packages/dyangalih/laravel-region)
[![Total Downloads](https://img.shields.io/packagist/dt/dyangalih/laravel-region.svg?style=flat-square)](https://packagist.org/packages/dyangalih/laravel-region)
[![License](https://img.shields.io/packagist/l/dyangalih/laravel-region.svg?style=flat-square)](https://packagist.org/packages/dyangalih/laravel-region)

A high-performance, governed Laravel package for Indonesian regional data (Provinces, Regencies, Districts, and Villages). Featuring a deep hierarchical REST API, global search capabilities, and optimized data ingestion.

## Features

- **Full Indonesian Hierarchy**: Provinces → Regencies → Districts → Villages.
- **Dual-Access API**: Support for both deep hierarchical and flat global endpoints.
- **Deep Eager Loading**: Automatically includes full parent hierarchy without N+1 queries.
- **High-Performance Seeding**: Chunked data ingestion for high-volume datasets.
- **Zero-Config Auto-Discovery**: Works out of the box with standard Laravel commands.

## Engineering Standards

This package was developed using an advanced AI-assisted engineering workflow to ensure production-grade stability:
- **[Spec-Kit](https://github.com/DyanGalih/spec-kit)**: Orchestrated development from specification to implementation.
- **Memory-Hub**, **Security-Review**, and **Architecture-Guard** enforced.

## Installation

```bash
composer require dyangalih/laravel-region
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

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
