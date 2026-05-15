<?php

namespace DyanGalih\LaravelRegion\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapInputName(SnakeCaseMapper::class)]
class VillageData extends Data
{
    public function __construct(
        public int $id,
        public int $districtId,
        public string $name,
        public DistrictData|Optional $district,
    ) {}
}
