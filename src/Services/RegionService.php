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
use Illuminate\Support\Facades\Cache;

class RegionService
{
    public function searchProvinces(?string $query = null, int $limit = 15): PaginatedDataCollection
    {
        $cacheKey = "provinces.search." . md5($query . $limit . request()->get('page', 1));

        return $this->remember($cacheKey, function () use ($query, $limit) {
            $q = Province::query();
            
            if ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            }

            return ProvinceData::collect($q->paginate($limit), PaginatedDataCollection::class);
        });
    }

    /**
     * Search regencies with filters.
     */
    public function searchRegencies(
        ?string $query = null,
        ?int $provinceId = null,
        int $limit = 15
    ): PaginatedDataCollection {
        $cacheKey = "regencies.search." . md5($query . $provinceId . $limit . request()->get('page', 1));

        return $this->remember($cacheKey, function () use ($query, $provinceId, $limit) {
            $queryBuilder = $this->regencyQuery();

            if ($query) {
                $queryBuilder->where('name', 'like', "%{$query}%");
            }

            if ($provinceId) {
                $queryBuilder->where('province_id', $provinceId);
            }

            return RegencyData::collect($queryBuilder->paginate($limit), PaginatedDataCollection::class);
        });
    }

    /**
     * Search districts with filters.
     */
    public function searchDistricts(
        ?string $query = null,
        ?int $provinceId = null,
        ?int $regencyId = null,
        int $limit = 15
    ): PaginatedDataCollection {
        $cacheKey = "districts.search." . md5($query . $provinceId . $regencyId . $limit . request()->get('page', 1));

        return $this->remember($cacheKey, function () use ($query, $provinceId, $regencyId, $limit) {
            $queryBuilder = $this->districtQuery();

            if ($query) {
                $queryBuilder->where('name', 'like', "%{$query}%");
            }

            if ($regencyId) {
                $regency = Regency::findOrFail($regencyId);
                
                if ($provinceId && $regency->province_id !== $provinceId) {
                    abort(404, 'Regency does not belong to the specified province.');
                }

                $queryBuilder->where('regency_id', $regencyId);
            }

            return DistrictData::collect($queryBuilder->paginate($limit), PaginatedDataCollection::class);
        });
    }

    /**
     * List villages with search and limit.
     */
    public function searchVillages(
        ?string $query = null,
        ?int $provinceId = null,
        ?int $regencyId = null,
        ?int $districtId = null,
        int $limit = 15
    ): PaginatedDataCollection {
        $cacheKey = "villages.search." . md5($query . $provinceId . $regencyId . $districtId . $limit . request()->get('page', 1));

        return $this->remember($cacheKey, function () use ($query, $provinceId, $regencyId, $districtId, $limit) {
            $queryBuilder = $this->villageQuery();

            if ($query) {
                $queryBuilder->where('name', 'like', "%{$query}%");
            }

            if ($districtId) {
                $district = District::with('regency')->findOrFail($districtId);

                if ($regencyId && $district->regency_id !== $regencyId) {
                    abort(404, 'District does not belong to the specified regency.');
                }

                if ($provinceId && $district->regency->province_id !== $provinceId) {
                    abort(404, 'District does not belong to the specified province.');
                }

                $queryBuilder->where('district_id', $districtId);
            }

            return VillageData::collect($queryBuilder->paginate($limit), PaginatedDataCollection::class);
        });
    }

    /**
     * Get details for a specific village with full parent hierarchy.
     */
    public function getVillageDetail(int $provinceId, int $regencyId, int $districtId, int $villageId): VillageData
    {
        $cacheKey = "village.detail.{$villageId}.{$districtId}.{$regencyId}.{$provinceId}";

        return $this->remember($cacheKey, function () use ($provinceId, $regencyId, $districtId, $villageId) {
            return VillageData::from(
                $this->villageQuery()
                    ->where('id', $villageId)
                    ->where('district_id', $districtId)
                    ->whereHas('district', function ($q) use ($regencyId, $provinceId) {
                        $q->where('regency_id', $regencyId)
                          ->whereHas('regency', function ($q) use ($provinceId) {
                              $q->where('province_id', $provinceId);
                          });
                    })
                    ->firstOrFail()
            );
        });
    }

    /**
     * Get details for a specific district with full parent hierarchy.
     */
    public function getDistrictDetail(int $provinceId, int $regencyId, int $districtId): DistrictData
    {
        $cacheKey = "district.detail.{$districtId}.{$regencyId}.{$provinceId}";

        return $this->remember($cacheKey, function () use ($provinceId, $regencyId, $districtId) {
            return DistrictData::from(
                $this->districtQuery()
                    ->where('id', $districtId)
                    ->where('regency_id', $regencyId)
                    ->whereHas('regency', function ($q) use ($provinceId) {
                        $q->where('province_id', $provinceId);
                    })
                    ->firstOrFail()
            );
        });
    }

    /**
     * Get details for a specific regency with full parent hierarchy.
     */
    public function getRegencyDetail(int $provinceId, int $regencyId): RegencyData
    {
        $cacheKey = "regency.detail.{$regencyId}.{$provinceId}";

        return $this->remember($cacheKey, function () use ($provinceId, $regencyId) {
            return RegencyData::from(
                $this->regencyQuery()
                    ->where('id', $regencyId)
                    ->where('province_id', $provinceId)
                    ->firstOrFail()
            );
        });
    }

    /**
     * Get details for a specific province.
     */
    public function getProvinceDetail(int $provinceId): ProvinceData
    {
        return $this->remember("province.detail.{$provinceId}", function () use ($provinceId) {
            return ProvinceData::from(Province::findOrFail($provinceId));
        });
    }

    /**
     * Get details for a specific village by ID with full parent hierarchy.
     */
    public function getVillageById(int $id): VillageData
    {
        return $this->remember("village.{$id}", function () use ($id) {
            return VillageData::from($this->villageQuery()->findOrFail($id));
        });
    }

    /**
     * Get details for a specific district by ID with full parent hierarchy.
     */
    public function getDistrictById(int $id): DistrictData
    {
        return $this->remember("district.{$id}", function () use ($id) {
            return DistrictData::from($this->districtQuery()->findOrFail($id));
        });
    }

    /**
     * Get details for a specific regency by ID with full parent hierarchy.
     */
    public function getRegencyById(int $id): RegencyData
    {
        return $this->remember("regency.{$id}", function () use ($id) {
            return RegencyData::from($this->regencyQuery()->findOrFail($id));
        });
    }

    /**
     * Internal helper to handle cached lookups.
     */
    protected function remember(string $key, \Closure $callback): mixed
    {
        $ttl = config('region.cache_ttl', 86400); // Default 24 hours
        
        if ($ttl === 0) {
            return $callback();
        }

        return Cache::remember("region.{$key}", $ttl, $callback);
    }

    /**
     * Base query for villages with full hierarchy.
     */
    protected function villageQuery()
    {
        return Village::query()->with(['district.regency.province']);
    }

    /**
     * Base query for districts with hierarchy.
     */
    protected function districtQuery()
    {
        return District::query()->with(['regency.province']);
    }

    /**
     * Base query for regencies with hierarchy.
     */
    protected function regencyQuery()
    {
        return Regency::query()->with(['province']);
    }
}
