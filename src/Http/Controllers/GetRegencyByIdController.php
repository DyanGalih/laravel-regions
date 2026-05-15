<?php

namespace DyanGalih\LaravelRegion\Http\Controllers;

use Illuminate\Routing\Controller;
use DyanGalih\LaravelRegion\Data\RegencyData;
use DyanGalih\LaravelRegion\Facades\Region;

class GetRegencyByIdController extends Controller
{
    public function __invoke(int $id): RegencyData
    {
        return Region::getRegencyById($id);
    }
}
