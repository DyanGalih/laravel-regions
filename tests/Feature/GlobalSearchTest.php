<?php

use DyanGalih\LaravelRegion\Tests\TestCase;
use DyanGalih\LaravelRegion\Models\Province;
use DyanGalih\LaravelRegion\Models\Regency;
use DyanGalih\LaravelRegion\Models\District;
use DyanGalih\LaravelRegion\Models\Village;

uses(TestCase::class);

beforeEach(function () {
    $this->province = Province::create(['id' => 11, 'name' => 'ACEH']);
    $this->regency = Regency::create(['id' => 1101, 'province_id' => 11, 'name' => 'KABUPATEN ACEH SELATAN']);
    $this->district = District::create(['id' => 1101010, 'regency_id' => 1101, 'name' => 'BAKONGAN']);
    $this->village = Village::create(['id' => 1101010001, 'district_id' => 1101010, 'name' => 'KEUDE BAKONGAN']);
});

it('can search villages globally using flat route', function () {
    $response = $this->getJson("/api/region/villages?q=Bakongan");
    
    $response->assertStatus(200)
             ->assertJsonCount(1, 'data')
             ->assertJsonPath('data.0.name', 'KEUDE BAKONGAN')
             ->assertJsonPath('data.0.district.name', 'BAKONGAN')
             ->assertJsonPath('data.0.district.regency.province.name', 'ACEH');
});

it('can search districts globally using flat route', function () {
    $response = $this->getJson("/api/region/districts?q=Bakongan");
    
    $response->assertStatus(200)
             ->assertJsonCount(1, 'data')
             ->assertJsonPath('data.0.name', 'BAKONGAN')
             ->assertJsonPath('data.0.regency.province.name', 'ACEH');
});
