<?php

namespace DyanGalih\LaravelRegion\Http\Controllers;

use Illuminate\Routing\Controller;
use DyanGalih\LaravelRegion\Data\RegencyData;
use DyanGalih\LaravelRegion\Facades\Region;

class GetRegencyDetailController extends Controller
{
    public function __invoke(int $provinceId, int $regencyId): RegencyData
    {
        return Region::getRegencyDetail($regencyId);
    }
}
