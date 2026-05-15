# Laravel Indonesian Region API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dyangalih/laravel-region.svg?style=flat-square)](https://packagist.org/packages/dyangalih/laravel-region)
[![Total Downloads](https://img.shields.io/packagist/dt/dyangalih/laravel-region.svg?style=flat-square)](https://packagist.org/packages/dyangalih/laravel-region)
[![License](https://img.shields.io/packagist/l/dyangalih/laravel-region.svg?style=flat-square)](https://packagist.org/packages/dyangalih/laravel-region)

A high-performance, governed Laravel package for Indonesian regional data (Provinces, Regencies, Districts, and Villages). Featuring a deep hierarchical REST API, robust request validation, and optimized data ingestion.

## Features

- **Full Indonesian Hierarchy**: Provinces → Regencies → Districts → Villages.
- **Deep RESTful API**: Intuitive hierarchical routing (e.g., `provinces/{id}/regencies`).
- **High-Performance Seeding**: Chunked data ingestion for high-volume datasets (Villages).
- **Governed Architecture**: Strict `camelCase` DTOs and FormRequest validation.
- **Facade Support**: Fluent programmatic access for internal application logic.

## Installation

You can install the package via composer:

```bash
composer require dyangalih/laravel-region
```

## Integration & Setup

### 1. Migrations
The package uses database migrations to create the necessary tables. You must publish the migration stubs to your root application:

```bash
php artisan vendor:publish --tag="region-migrations"
php artisan migrate
```

### 2. Seeding Data
Once migrations are complete, you can populate the tables using the high-performance seeder command. This command will read the regional CSV data and perform chunked inserts:

```bash
php artisan indonesia:seed
```

#### Integration with Root `DatabaseSeeder`
To include the regional data as part of your standard application setup, you can call the command from your `database/seeders/DatabaseSeeder.php`:

```php
public function run(): void
{
    $this->command->call('indonesia:seed');
}
```

### 3. Enabling Routes
The package automatically registers its routes via the `RegionServiceProvider`. By default, all endpoints are prefixed with `/api/region` and protected by the `api` middleware.

To verify the routes are active in your application, run:
```bash
php artisan route:list --path=api/region
```

#### Customizing Middleware
If you need to change the middleware or other routing configurations, publish the config file:
```bash
php artisan vendor:publish --tag="region-config"
```
Then, modify `config/region.php` to suit your requirements.

## API Usage

All routes are prefixed with `/api/region` and are governed by the middleware defined in your configuration.

### Hierarchical Endpoints

| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `GET` | `/provinces` | List all provinces |
| `GET` | `/provinces/{id}` | Get province details |
| `GET` | `/provinces/{id}/regencies` | List regencies in a province |
| `GET` | `/provinces/{pId}/regencies/{rId}` | Get regency details |
| `GET` | `/provinces/{pId}/regencies/{rId}/districts` | List districts in a regency |
| `GET` | `/provinces/{pId}/regencies/{rId}/districts/{dId}/villages` | List villages in a district |

### Query Parameters

All list endpoints support the following parameters:
- `q`: Search by name (string).
- `limit`: Pagination limit (default: 15, max: 100).

## Programmatic Usage

You can access the regional data directly in your application logic using the `Region` facade:

```php
use DyanGalih\LaravelRegion\Facades\Region;

// Search provinces
$provinces = Region::searchProvinces('JAWA', limit: 10);

// Get specific village with parent hierarchy
$village = Region::getVillageDetail($villageId);
echo $village->district->regency->province->name;
```

## Development

To develop or contribute to this plugin:

1. **Clone the repository**:
   ```bash
   git clone https://github.com/DyanGalih/laravel-region.git
   cd laravel-region
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Running Tests**:
   The package uses Pest PHP for testing. Ensure your environment is set up and run:
   ```bash
   composer test
   ```

4. **Code Quality**:
   We use Laravel Pint for code styling. Please run it before submitting PRs:
   ```bash
   composer lint
   ```

## Security

If you discover any security-related issues, please email galih@example.com instead of using the issue tracker.

## Credits

- [Dyan Galih](https://github.com/DyanGalih)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
