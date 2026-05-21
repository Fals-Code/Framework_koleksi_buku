<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungans';

    protected $fillable = [
        'nfc_card_id',
        'waktu_masuk',
        'waktu_keluar',
        'tujuan',
    ];

    protected $casts = [
        'waktu_masuk'  => 'datetime',
        'waktu_keluar' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────

    /**
     * Kunjungan milik satu kartu NFC (anggota).
     */
    public function nfcCard(): BelongsTo
    {
        return $this->belongsTo(NfcCard::class);
    }

    // ─── Scopes ───────────────────────────────────────────────

    /**
     * Scope: kunjungan yang masih aktif (belum tap keluar).
     */
    public function scopeAktif($query)
    {
        return $query->whereNull('waktu_keluar');
    }

    /**
     * Scope: kunjungan hari ini.
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('waktu_masuk', today());
    }
}
