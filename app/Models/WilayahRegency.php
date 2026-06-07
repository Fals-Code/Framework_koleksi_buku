<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahRegency extends Model
{
    protected $table = 'wilayah_regencies';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'province_id', 'name'];

    public function province()
    {
        return $this->belongsTo(WilayahProvince::class, 'province_id');
    }

    public function districts()
    {
        return $this->hasMany(WilayahDistrict::class, 'regency_id');
    }
}
