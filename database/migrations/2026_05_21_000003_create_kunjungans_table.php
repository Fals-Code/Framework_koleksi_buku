<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel kunjungans: Mencatat absensi kunjungan perpustakaan
     */
    public function up(): void
    {
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nfc_card_id')->constrained('nfc_cards')->cascadeOnDelete();
            $table->datetime('waktu_masuk')->comment('Waktu tap masuk');
            $table->datetime('waktu_keluar')->nullable()->comment('Waktu tap keluar');
            $table->string('tujuan')->nullable()->comment('Tujuan kunjungan');
            $table->timestamps();

            $table->index('nfc_card_id');
            $table->index('waktu_masuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};
