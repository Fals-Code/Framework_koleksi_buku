<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_antrian');
            $table->string('nama_pengunjung');
            $table->string('nim')->nullable();
            $table->string('keperluan')->nullable();
            $table->enum('status', ['menunggu', 'dipanggil', 'selesai', 'terlewat'])->default('menunggu');
            $table->string('loket')->nullable();
            $table->date('tanggal_antrian');
            $table->dateTime('waktu_daftar');
            $table->dateTime('waktu_dipanggil')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};
