<?php

namespace DyanGalih\LaravelRegion\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapInputName(SnakeCaseMapper::class)]
class DistrictData extends Data
{
    public function __construct(
        public int $id,
        public int $regencyId,
        public string $name,
        public RegencyData|Optional $regency,
    ) {}
}
