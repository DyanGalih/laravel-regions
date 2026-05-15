<?php

namespace DyanGalih\LaravelRegion\Http\Controllers;

use Illuminate\Routing\Controller;
use Spatie\LaravelData\PaginatedDataCollection;
use DyanGalih\LaravelRegion\Facades\Region;
use DyanGalih\LaravelRegion\Http\Requests\RegionSearchRequest;

class SearchDistrictsController extends Controller
{
    public function __invoke(RegionSearchRequest $request): PaginatedDataCollection
    {
        return Region::searchDistricts(
            $request->query('q'),
            null,
            $request->query('regency_id') ? (int) $request->query('regency_id') : null,
            (int) $request->query('limit', 15)
        );
    }
}
