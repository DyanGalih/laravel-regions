<?php

namespace DyanGalih\LaravelRegion\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class ProvinceData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
