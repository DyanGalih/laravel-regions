<?php

namespace DyanGalih\LaravelRegion\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use DyanGalih\LaravelRegion\RegionServiceProvider;

class TestCase extends Orchestra
{

    protected function getPackageProviders($app)
    {
        return [
            RegionServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        config()->set('data', [
            'validation_strategy' => 'disabled',
            'max_transformation_depth' => null,
            'date_format' => DATE_ATOM,
            'transformers' => [],
            'casts' => [],
            'rule_inferrers' => [],
            'normalizers' => [
                \Spatie\LaravelData\Normalizers\ModelNormalizer::class,
                \Spatie\LaravelData\Normalizers\ArrayableNormalizer::class,
                \Spatie\LaravelData\Normalizers\ObjectNormalizer::class,
                \Spatie\LaravelData\Normalizers\ArrayNormalizer::class,
                \Spatie\LaravelData\Normalizers\JsonNormalizer::class,
            ],
        ]);

        $migrations = [
            '2024_01_01_000001_create_indonesia_provinces_table',
            '2024_01_01_000002_create_indonesia_regencies_table',
            '2024_01_01_000003_create_indonesia_districts_table',
            '2024_01_01_000004_create_indonesia_villages_table',
        ];

        foreach ($migrations as $migration) {
            $m = include __DIR__ . "/../database/migrations/{$migration}.php";
            $m->up();
        }
    }
}
