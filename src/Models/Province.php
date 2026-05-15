<?php

namespace DyanGalih\LaravelRegion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = ['id', 'name'];

    public function getTable()
    {
        return config('region.table_prefix', 'indonesia_') . 'provinces';
    }

    public function regencies(): HasMany
    {
        return $this->hasMany(Regency::class, 'province_id');
    }
}
