<?php

namespace DyanGalih\LaravelRegion\Http\Controllers;

use Illuminate\Routing\Controller;
use DyanGalih\LaravelRegion\Data\DistrictData;
use DyanGalih\LaravelRegion\Facades\Region;

class GetDistrictByIdController extends Controller
{
    public function __invoke(int $id): DistrictData
    {
        return Region::getDistrictById($id);
    }
}
