<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahVillage extends Model
{
    protected $table = 'wilayah_villages';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'district_id', 'name'];

    public function district()
    {
        return $this->belongsTo(WilayahDistrict::class, 'district_id');
    }
}
