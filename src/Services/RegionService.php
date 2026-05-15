<?php

namespace DyanGalih\LaravelRegion\Services;

use Spatie\LaravelData\PaginatedDataCollection;
use DyanGalih\LaravelRegion\Data\DistrictData;
use DyanGalih\LaravelRegion\Data\ProvinceData;
use DyanGalih\LaravelRegion\Data\RegencyData;
use DyanGalih\LaravelRegion\Data\VillageData;
use DyanGalih\LaravelRegion\Models\District;
use DyanGalih\LaravelRegion\Models\Province;
use DyanGalih\LaravelRegion\Models\Regency;
use DyanGalih\LaravelRegion\Models\Village;

class RegionService
{
    /**
     * List provinces with search and limit.
     */
    public function searchProvinces(?string $query = null, int $limit = 15): PaginatedDataCollection
    {
        $q = Province::query();
        
        if ($query) {
            $q->where('name', 'LIKE', "%{$query}%");
        }

        return ProvinceData::collect($q->paginate($limit), PaginatedDataCollection::class);
    }

    /**
     * List regencies with search and limit.
     */
    public function searchRegencies(?string $query = null, ?int $provinceId = null, int $limit = 15): PaginatedDataCollection
    {
        $q = Regency::query();
        
        if ($query) {
            $q->where('name', 'LIKE', "%{$query}%");
        }

        if ($provinceId) {
            $q->where('province_id', $provinceId);
        }

        return RegencyData::collect($q->paginate($limit), PaginatedDataCollection::class);
    }

    /**
     * List districts with search and limit.
     */
    public function searchDistricts(?string $query = null, ?int $regencyId = null, int $limit = 15): PaginatedDataCollection
    {
        $q = District::query();
        
        if ($query) {
            $q->where('name', 'LIKE', "%{$query}%");
        }

        if ($regencyId) {
            $q->where('regency_id', $regencyId);
        }

        return DistrictData::collect($q->paginate($limit), PaginatedDataCollection::class);
    }

    /**
     * List villages with search and limit.
     */
    public function searchVillages(?string $query = null, ?int $districtId = null, int $limit = 15): PaginatedDataCollection
    {
        $q = Village::query();
        
        if ($query) {
            $q->where('name', 'LIKE', "%{$query}%");
        }

        if ($districtId) {
            $q->where('district_id', $districtId);
        }

        return VillageData::collect($q->paginate($limit), PaginatedDataCollection::class);
    }

    /**
     * Get details for a specific village with full parent hierarchy.
     */
    public function getVillageDetail(int $villageId): VillageData
    {
        $village = Village::with(['district.regency.province'])->findOrFail($villageId);

        return VillageData::from($village);
    }

    /**
     * Get details for a specific district with full parent hierarchy.
     */
    public function getDistrictDetail(int $districtId): DistrictData
    {
        $district = District::with(['regency.province'])->findOrFail($districtId);

        return DistrictData::from($district);
    }

    /**
     * Get details for a specific regency with full parent hierarchy.
     */
    public function getRegencyDetail(int $regencyId): RegencyData
    {
        $regency = Regency::with(['province'])->findOrFail($regencyId);

        return RegencyData::from($regency);
    }

    /**
     * Get details for a specific province.
     */
    public function getProvinceDetail(int $provinceId): ProvinceData
    {
        $province = Province::findOrFail($provinceId);

        return ProvinceData::from($province);
    }
}
