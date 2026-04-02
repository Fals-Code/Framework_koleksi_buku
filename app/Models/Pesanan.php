<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id', 'nama_pelanggan', 'nomor_pesanan', 'total_harga', 'catatan', 'status', 'snap_token'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}
