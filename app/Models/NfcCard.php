<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NfcCard extends Model
{
    use HasFactory;

    protected $table = 'nfc_cards';

    protected $fillable = [
        'serial_number',
        'nama_anggota',
        'nim',
        'email',
        'user_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────

    /**
     * Kartu NFC bisa milik satu User (opsional).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Satu kartu NFC bisa punya banyak transaksi peminjaman.
     */
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Satu kartu NFC bisa punya banyak catatan kunjungan.
     */
    public function kunjungans(): HasMany
    {
        return $this->hasMany(Kunjungan::class);
    }

    // ─── Scopes ───────────────────────────────────────────────

    /**
     * Scope: hanya kartu yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ─── Accessors ────────────────────────────────────────────

    /**
     * Hitung jumlah buku yang sedang dipinjam oleh anggota ini.
     */
    public function getActiveBorrowsCountAttribute(): int
    {
        return $this->peminjamans()->where('status', 'dipinjam')->count();
    }
}
