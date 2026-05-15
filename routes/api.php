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

Route::prefix('api/region')->middleware(config('region.middleware'))->group(function () {
    // Provinces Hierarchy
    Route::prefix('provinces')->group(function () {
        Route::get('/', ListProvincesController::class)->name('region.provinces.index');
        Route::get('{provinceId}', GetProvinceDetailController::class)->name('region.provinces.show');

        // Nested Regencies
        Route::prefix('{provinceId}/regencies')->group(function () {
            Route::get('/', GetRegenciesByProvinceController::class)->name('region.provinces.regencies.index');
            Route::get('{regencyId}', GetRegencyDetailController::class)->name('region.provinces.regencies.show');

            // Nested Districts
            Route::prefix('{regencyId}/districts')->group(function () {
                Route::get('/', GetDistrictsByRegencyController::class)->name('region.provinces.regencies.districts.index');
                Route::get('{districtId}', GetDistrictDetailController::class)->name('region.provinces.regencies.districts.show');

                // Nested Villages
                Route::prefix('{districtId}/villages')->group(function () {
                    Route::get('/', GetVillagesByDistrictController::class)->name('region.provinces.regencies.districts.villages.index');
                    Route::get('{villageId}', GetVillageDetailController::class)->name('region.provinces.regencies.districts.villages.show');
                });
            });
        });
    });
});
