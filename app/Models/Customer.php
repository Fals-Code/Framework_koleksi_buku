<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'alamat',
        'provinsi',
        'kota',
        'kecamatan',
        'kodepos',
        'foto_blob',
        'foto_path',
    ];
}
