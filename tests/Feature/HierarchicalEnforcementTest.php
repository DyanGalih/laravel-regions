<?php

use DyanGalih\LaravelRegion\Tests\TestCase;
use DyanGalih\LaravelRegion\Models\Province;
use DyanGalih\LaravelRegion\Models\Regency;
use DyanGalih\LaravelRegion\Models\District;
use DyanGalih\LaravelRegion\Models\Village;

uses(TestCase::class);

beforeEach(function () {
    $this->province1 = Province::create(['id' => 11, 'name' => 'Province 1']);
    $this->province2 = Province::create(['id' => 12, 'name' => 'Province 2']);
    
    $this->regency1 = Regency::create(['id' => 1101, 'province_id' => 11, 'name' => 'Regency 1']);
    $this->regency2 = Regency::create(['id' => 1201, 'province_id' => 12, 'name' => 'Regency 2']);

    $this->district1 = District::create(['id' => 1101010, 'regency_id' => 1101, 'name' => 'District 1']);
    $this->village1 = Village::create(['id' => 1101010001, 'district_id' => 1101010, 'name' => 'Village 1']);
});

it('returns 404 when regency does not belong to province', function () {
    // Regency 1 belongs to Province 1 (11). Trying to access via Province 2 (12).
    $response = $this->getJson("/api/region/provinces/12/regencies/1101");
    $response->assertStatus(404);
});

it('returns 404 when listing districts with mismatched province and regency', function () {
    // Regency 1 belongs to Province 1 (11). Trying to list districts via Province 2 (12).
    $response = $this->getJson("/api/region/provinces/12/regencies/1101/districts");
    $response->assertStatus(404);
});

it('returns 404 when village does not belong to district', function () {
    // Village 1 belongs to District 1.
    // Create another district in the same regency/province.
    $district2 = District::create(['id' => 1101020, 'regency_id' => 1101, 'name' => 'District 2']);
    
    // Trying to access Village 1 via District 2.
    $response = $this->getJson("/api/region/provinces/11/regencies/1101/districts/1101020/villages/1101010001");
    $response->assertStatus(404);
});

it('allows access when hierarchy is correct', function () {
    $response = $this->getJson("/api/region/provinces/11/regencies/1101");
    $response->assertStatus(200)
             ->assertJsonPath('id', 1101);
});
