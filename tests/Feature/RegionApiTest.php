<?php

use DyanGalih\LaravelRegion\Models\Province;
use DyanGalih\LaravelRegion\Models\Regency;
use DyanGalih\LaravelRegion\Models\District;
use DyanGalih\LaravelRegion\Models\Village;
use DyanGalih\LaravelRegion\Tests\TestCase;

uses(TestCase::class);

it('can list provinces with limit', function () {
    Province::create(['id' => 11, 'name' => 'ACEH']);
    Province::create(['id' => 12, 'name' => 'SUMATERA UTARA']);

    $response = $this->getJson('/api/region/provinces?limit=1');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

it('can search regencies under a province', function () {
    Province::create(['id' => 11, 'name' => 'ACEH']);
    Regency::create(['id' => 1101, 'province_id' => 11, 'name' => 'KAB. ACEH SELATAN']);

    $response = $this->getJson('/api/region/provinces/11/regencies?q=SELATAN');

    $response->assertStatus(200)
        ->assertJsonFragment(['name' => 'KAB. ACEH SELATAN', 'provinceId' => 11]);
});

it('can get village detail with full parent hierarchy', function () {
    Province::create(['id' => 11, 'name' => 'ACEH']);
    Regency::create(['id' => 1101, 'province_id' => 11, 'name' => 'KAB. ACEH SELATAN']);
    District::create(['id' => 110101, 'regency_id' => 1101, 'name' => 'BAKONGAN']);
    Village::create(['id' => 1101012001, 'district_id' => 110101, 'name' => 'KEUDE BAKONGAN']);

    $response = $this->getJson('/api/region/provinces/11/regencies/1101/districts/110101/villages/1101012001');

    $response->assertStatus(200)
        ->assertJsonPath('name', 'KEUDE BAKONGAN')
        ->assertJsonPath('district.name', 'BAKONGAN')
        ->assertJsonPath('district.regency.name', 'KAB. ACEH SELATAN')
        ->assertJsonPath('district.regency.province.name', 'ACEH');
});
