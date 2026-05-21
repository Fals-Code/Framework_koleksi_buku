<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel nfc_cards: Menyimpan mapping kartu NFC → anggota perpustakaan
     */
    public function up(): void
    {
        Schema::create('nfc_cards', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number', 100)->unique()->comment('UID dari tag NFC');
            $table->string('nama_anggota', 200)->comment('Nama mahasiswa/anggota');
            $table->string('nim', 50)->nullable()->comment('NIM mahasiswa');
            $table->string('email')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true)->comment('Status aktif kartu');
            $table->timestamps();

            $table->index('serial_number');
            $table->index('nim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nfc_cards');
    }
};
