<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'nfc_card_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'petugas',
    ];

    protected $casts = [
        'tanggal_pinjam'  => 'datetime',
        'tanggal_kembali' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────

    /**
     * Peminjaman milik satu kartu NFC (anggota).
     */
    public function nfcCard(): BelongsTo
    {
        return $this->belongsTo(NfcCard::class);
    }

    /**
     * Peminjaman merujuk satu buku.
     */
    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    // ─── Scopes ───────────────────────────────────────────────

    /**
     * Scope: hanya peminjaman yang belum dikembalikan.
     */
    public function scopeDipinjam($query)
    {
        return $query->where('status', 'dipinjam');
    }

    /**
     * Scope: hanya yang sudah dikembalikan.
     */
    public function scopeDikembalikan($query)
    {
        return $query->where('status', 'dikembalikan');
    }
}
