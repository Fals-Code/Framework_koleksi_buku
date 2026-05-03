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
        Schema::table('customers', function (Blueprint $table) {
            $table->text('alamat')->nullable()->after('telepon');
            $table->string('provinsi', 100)->nullable()->after('alamat');
            $table->string('kota', 100)->nullable()->after('provinsi');
            $table->string('kecamatan', 100)->nullable()->after('kota');
            $table->string('kodepos', 20)->nullable()->after('kecamatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'provinsi', 'kota', 'kecamatan', 'kodepos']);
        });
    }
};
