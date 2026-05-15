<?php

use Illuminate\Support\Facades\Route;
use DyanGalih\LaravelRegion\Http\Controllers\ListProvincesController;
use DyanGalih\LaravelRegion\Http\Controllers\GetProvinceDetailController;
use DyanGalih\LaravelRegion\Http\Controllers\GetRegenciesByProvinceController;
use DyanGalih\LaravelRegion\Http\Controllers\GetRegencyDetailController;
use DyanGalih\LaravelRegion\Http\Controllers\GetDistrictsByRegencyController;
use DyanGalih\LaravelRegion\Http\Controllers\GetDistrictDetailController;
use DyanGalih\LaravelRegion\Http\Controllers\GetVillagesByDistrictController;
use DyanGalih\LaravelRegion\Http\Controllers\GetVillageDetailController;
use DyanGalih\LaravelRegion\Http\Controllers\SearchRegenciesController;
use DyanGalih\LaravelRegion\Http\Controllers\SearchDistrictsController;
use DyanGalih\LaravelRegion\Http\Controllers\SearchVillagesController;
use DyanGalih\LaravelRegion\Http\Controllers\GetRegencyByIdController;
use DyanGalih\LaravelRegion\Http\Controllers\GetDistrictByIdController;
use DyanGalih\LaravelRegion\Http\Controllers\GetVillageByIdController;

Route::prefix('api/region')
    ->middleware(config('region.middleware'))
    ->name('region.')
    ->group(function () {
        // Global Flat Routes
        Route::prefix('regencies')->name('regencies.')->group(function () {
            Route::get('/', SearchRegenciesController::class)->name('index');
            Route::get('{id}', GetRegencyByIdController::class)->name('show');
        });

        Route::prefix('districts')->name('districts.')->group(function () {
            Route::get('/', SearchDistrictsController::class)->name('index');
            Route::get('{id}', GetDistrictByIdController::class)->name('show');
        });

        Route::prefix('villages')->name('villages.')->group(function () {
            Route::get('/', SearchVillagesController::class)->name('index');
            Route::get('{id}', GetVillageByIdController::class)->name('show');
        });

        // Provinces Hierarchy
        Route::prefix('provinces')->name('provinces.')->group(function () {
            Route::get('/', ListProvincesController::class)->name('index');
            Route::get('{provinceId}', GetProvinceDetailController::class)->name('show');

            // Nested Regencies
            Route::prefix('{provinceId}/regencies')->name('regencies.')->group(function () {
                Route::get('/', GetRegenciesByProvinceController::class)->name('index');
                Route::get('{regencyId}', GetRegencyDetailController::class)->name('show');

                // Nested Districts
                Route::prefix('{regencyId}/districts')->name('districts.')->group(function () {
                    Route::get('/', GetDistrictsByRegencyController::class)->name('index');
                    Route::get('{districtId}', GetDistrictDetailController::class)->name('show');

                    // Nested Villages
                    Route::prefix('{districtId}/villages')->name('villages.')->group(function () {
                        Route::get('/', GetVillagesByDistrictController::class)->name('index');
                        Route::get('{villageId}', GetVillageDetailController::class)->name('show');
                    });
                });
            });
        });

        // Backward compatibility Global Search (keep search prefix if needed)
        Route::prefix('search')->name('search.')->group(function () {
            Route::get('regencies', SearchRegenciesController::class)->name('regencies');
            Route::get('districts', SearchDistrictsController::class)->name('districts');
            Route::get('villages', SearchVillagesController::class)->name('villages');
        });
    });
