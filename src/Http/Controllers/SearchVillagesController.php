<?php

namespace DyanGalih\LaravelRegion\Http\Controllers;

use Illuminate\Routing\Controller;
use Spatie\LaravelData\PaginatedDataCollection;
use DyanGalih\LaravelRegion\Facades\Region;
use DyanGalih\LaravelRegion\Http\Requests\RegionSearchRequest;

class SearchVillagesController extends Controller
{
    public function __invoke(RegionSearchRequest $request): PaginatedDataCollection
    {
        return Region::searchVillages(
            $request->query('q'),
            $request->query('district_id'),
            (int) $request->query('limit', 15)
        );
    }
}
