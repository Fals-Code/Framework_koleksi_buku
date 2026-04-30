<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('email', 150)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->binary('foto_blob')->nullable();
            $table->string('foto_path')->nullable();
            $table->timestamps();
        });

        // Modifikasi kolom foto_blob menjadi LONGBLOB agar benar-benar LONGBLOB (di atas 64KB)
        DB::statement("ALTER TABLE customers MODIFY foto_blob LONGBLOB");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
