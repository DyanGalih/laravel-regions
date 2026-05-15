<?php

namespace DyanGalih\LaravelRegion\Http\Controllers;

use Illuminate\Routing\Controller;
use Spatie\LaravelData\PaginatedDataCollection;
use DyanGalih\LaravelRegion\Facades\Region;
use DyanGalih\LaravelRegion\Http\Requests\RegionListRequest;

class GetRegenciesByProvinceController extends Controller
{
    public function __invoke(RegionListRequest $request, int $provinceId): PaginatedDataCollection
    {
        return Region::searchRegencies(
            $request->query('q'),
            $provinceId,
            (int) $request->query('limit', 15)
        );
    }
}
