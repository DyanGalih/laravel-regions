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
            // Validate province exists
            Province::findOrFail($provinceId);
            $q->where('province_id', $provinceId);
        }

        return RegencyData::collect($q->paginate($limit), PaginatedDataCollection::class);
    }

    /**
     * List districts with search and limit.
     */
    public function searchDistricts(?string $query = null, ?int $provinceId = null, ?int $regencyId = null, int $limit = 15): PaginatedDataCollection
    {
        $q = District::query();
        
        if ($query) {
            $q->where('name', 'LIKE', "%{$query}%");
        }

        if ($regencyId) {
            $regency = Regency::findOrFail($regencyId);
            
            if ($provinceId && $regency->province_id !== $provinceId) {
                abort(404, 'Regency does not belong to the specified province.');
            }

            $q->where('regency_id', $regencyId);
        }

        return DistrictData::collect($q->paginate($limit), PaginatedDataCollection::class);
    }

    /**
     * List villages with search and limit.
     */
    public function searchVillages(?string $query = null, ?int $provinceId = null, ?int $regencyId = null, ?int $districtId = null, int $limit = 15): PaginatedDataCollection
    {
        $q = Village::query();
        
        if ($query) {
            $q->where('name', 'LIKE', "%{$query}%");
        }

        if ($districtId) {
            $district = District::with('regency')->findOrFail($districtId);

            if ($regencyId && $district->regency_id !== $regencyId) {
                abort(404, 'District does not belong to the specified regency.');
            }

            if ($provinceId && $district->regency->province_id !== $provinceId) {
                abort(404, 'District does not belong to the specified province.');
            }

            $q->where('district_id', $districtId);
        }

        return VillageData::collect($q->paginate($limit), PaginatedDataCollection::class);
    }

    /**
     * Get details for a specific village with full parent hierarchy.
     */
    public function getVillageDetail(int $provinceId, int $regencyId, int $districtId, int $villageId): VillageData
    {
        $village = Village::where('id', $villageId)
            ->where('district_id', $districtId)
            ->whereHas('district', function ($q) use ($regencyId, $provinceId) {
                $q->where('regency_id', $regencyId)
                  ->whereHas('regency', function ($q) use ($provinceId) {
                      $q->where('province_id', $provinceId);
                  });
            })
            ->with(['district.regency.province'])
            ->firstOrFail();

        return VillageData::from($village);
    }

    /**
     * Get details for a specific district with full parent hierarchy.
     */
    public function getDistrictDetail(int $provinceId, int $regencyId, int $districtId): DistrictData
    {
        $district = District::where('id', $districtId)
            ->where('regency_id', $regencyId)
            ->whereHas('regency', function ($q) use ($provinceId) {
                $q->where('province_id', $provinceId);
            })
            ->with(['regency.province'])
            ->firstOrFail();

        return DistrictData::from($district);
    }

    /**
     * Get details for a specific regency with full parent hierarchy.
     */
    public function getRegencyDetail(int $provinceId, int $regencyId): RegencyData
    {
        $regency = Regency::where('id', $regencyId)
            ->where('province_id', $provinceId)
            ->with(['province'])
            ->firstOrFail();

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
