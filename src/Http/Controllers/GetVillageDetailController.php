<?php

namespace DyanGalih\LaravelRegion\Http\Controllers;

use Illuminate\Routing\Controller;
use DyanGalih\LaravelRegion\Data\VillageData;
use DyanGalih\LaravelRegion\Facades\Region;

class GetVillageDetailController extends Controller
{
    public function __invoke(int $villageId): VillageData
    {
        return Region::getVillageDetail($villageId);
    }
}
