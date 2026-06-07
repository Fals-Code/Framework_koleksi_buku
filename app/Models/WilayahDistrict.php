<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahDistrict extends Model
{
    protected $table = 'wilayah_districts';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'regency_id', 'name'];

    public function regency()
    {
        return $this->belongsTo(WilayahRegency::class, 'regency_id');
    }

    public function villages()
    {
        return $this->hasMany(WilayahVillage::class, 'district_id');
    }
}
