<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel peminjamans: Mencatat transaksi peminjaman & pengembalian buku
     */
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nfc_card_id')->constrained('nfc_cards')->cascadeOnDelete();
            $table->foreignId('buku_id')->constrained('bukus')->cascadeOnDelete();
            $table->datetime('tanggal_pinjam')->comment('Waktu peminjaman');
            $table->datetime('tanggal_kembali')->nullable()->comment('Waktu pengembalian (null = belum kembali)');
            $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam');
            $table->string('petugas')->nullable()->comment('Nama petugas yang memproses');
            $table->timestamps();

            $table->index('nfc_card_id');
            $table->index('buku_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
