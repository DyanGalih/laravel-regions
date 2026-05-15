<?php

namespace DyanGalih\LaravelRegion\Http\Controllers;

use Illuminate\Routing\Controller;
use Spatie\LaravelData\PaginatedDataCollection;
use DyanGalih\LaravelRegion\Facades\Region;
use DyanGalih\LaravelRegion\Http\Requests\RegionListRequest;

class GetDistrictsByRegencyController extends Controller
{
    public function __invoke(RegionListRequest $request, int $provinceId, int $regencyId): PaginatedDataCollection
    {
        return Region::searchDistricts(
            null,
            $provinceId,
            $regencyId,
            (int) $request->query('limit', 15)
        );
    }
}
