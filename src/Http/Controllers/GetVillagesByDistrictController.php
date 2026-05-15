<?php

namespace DyanGalih\LaravelRegion\Http\Controllers;

use Illuminate\Routing\Controller;
use Spatie\LaravelData\PaginatedDataCollection;
use DyanGalih\LaravelRegion\Facades\Region;
use DyanGalih\LaravelRegion\Http\Requests\RegionListRequest;

class GetVillagesByDistrictController extends Controller
{
    public function __invoke(RegionListRequest $request, int $provinceId, int $regencyId, int $districtId): PaginatedDataCollection
    {
        return Region::searchVillages(
            $request->query('q'),
            $provinceId,
            $regencyId,
            $districtId,
            (int) $request->query('limit', 15)
        );
    }
}
