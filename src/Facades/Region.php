<?php

namespace DyanGalih\LaravelRegion\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DyanGalih\LaravelRegion\Services\RegionService
 */
class Region extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'region';
    }
}
