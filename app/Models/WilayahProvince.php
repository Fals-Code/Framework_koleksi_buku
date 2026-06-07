<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahProvince extends Model
{
    protected $table = 'wilayah_provinces';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'name'];

    public function regencies()
    {
        return $this->hasMany(WilayahRegency::class, 'province_id');
    }
}
