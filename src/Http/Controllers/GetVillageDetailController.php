<?php

namespace DyanGalih\LaravelRegion\Http\Controllers;

use Illuminate\Routing\Controller;
use DyanGalih\LaravelRegion\Data\VillageData;
use DyanGalih\LaravelRegion\Facades\Region;

class GetVillageDetailController extends Controller
{
    public function __invoke(int $provinceId, int $regencyId, int $districtId, int $villageId): VillageData
    {
        return Region::getVillageDetail($provinceId, $regencyId, $districtId, $villageId);
    }
}
