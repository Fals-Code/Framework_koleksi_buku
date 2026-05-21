<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_antrian',
        'nama_pengunjung',
        'nim',
        'keperluan',
        'status',
        'loket',
        'tanggal_antrian',
        'waktu_daftar',
        'waktu_dipanggil',
        'waktu_selesai',
    ];

    protected $casts = [
        'tanggal_antrian' => 'date',
        'waktu_daftar' => 'datetime',
        'waktu_dipanggil' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal_antrian', today());
    }

    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeDipanggil($query)
    {
        return $query->where('status', 'dipanggil');
    }

    public function scopeTerlewat($query)
    {
        return $query->where('status', 'terlewat');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public static function generateNomorAntrian()
    {
        $lastAntrian = self::hariIni()
            ->lockForUpdate()
            ->orderBy('id', 'desc')
            ->first();
        
        if (!$lastAntrian) {
            return 'A-001';
        }

        $lastNumber = (int) substr($lastAntrian->nomor_antrian, 2);
        $newNumber = $lastNumber + 1;
        
        return 'A-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
