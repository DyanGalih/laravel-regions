<?php

namespace DyanGalih\LaravelRegion\Http\Controllers;

use Illuminate\Routing\Controller;
use DyanGalih\LaravelRegion\Data\VillageData;
use DyanGalih\LaravelRegion\Facades\Region;

class GetVillageByIdController extends Controller
{
    public function __invoke(int $id): VillageData
    {
        return Region::getVillageById($id);
    }
}
