<?php

namespace DyanGalih\LaravelRegion\Http\Controllers;

use Illuminate\Routing\Controller;
use DyanGalih\LaravelRegion\Data\DistrictData;
use DyanGalih\LaravelRegion\Facades\Region;

class GetDistrictDetailController extends Controller
{
    public function __invoke(int $provinceId, int $regencyId, int $districtId): DistrictData
    {
        return Region::getDistrictDetail($districtId);
    }
}
