<?php

namespace DyanGalih\LaravelRegion\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapInputName(SnakeCaseMapper::class)]
class RegencyData extends Data
{
    public function __construct(
        public int $id,
        public int $provinceId,
        public string $name,
        public ProvinceData|Optional $province,
    ) {}
}
