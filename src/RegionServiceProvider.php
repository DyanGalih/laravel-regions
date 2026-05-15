<?php

namespace DyanGalih\LaravelRegion;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use DyanGalih\LaravelRegion\Commands\SeedRegionsCommand;
use DyanGalih\LaravelRegion\Services\RegionService;

class RegionServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-region')
            ->hasConfigFile('region')
            ->hasMigrations([
                '2024_01_01_000001_create_indonesia_provinces_table',
                '2024_01_01_000002_create_indonesia_regencies_table',
                '2024_01_01_000003_create_indonesia_districts_table',
                '2024_01_01_000004_create_indonesia_villages_table',
            ])
            ->runsMigrations()
            ->hasRoute('api')
            ->hasCommand(SeedRegionsCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(RegionService::class, function () {
            return new RegionService();
        });

        $this->app->alias(RegionService::class, 'region');
    }
}
