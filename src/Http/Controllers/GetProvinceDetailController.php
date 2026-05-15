<?php

namespace DyanGalih\LaravelRegion\Http\Controllers;

use Illuminate\Routing\Controller;
use DyanGalih\LaravelRegion\Data\ProvinceData;
use DyanGalih\LaravelRegion\Facades\Region;

class GetProvinceDetailController extends Controller
{
    public function __invoke(int $provinceId): ProvinceData
    {
        return Region::getProvinceDetail($provinceId);
    }
}
