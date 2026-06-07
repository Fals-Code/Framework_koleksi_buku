<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayah_provinces', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('wilayah_regencies', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('province_id')->index();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('wilayah_districts', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('regency_id')->index();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('wilayah_villages', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('district_id')->index();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah_villages');
        Schema::dropIfExists('wilayah_districts');
        Schema::dropIfExists('wilayah_regencies');
        Schema::dropIfExists('wilayah_provinces');
    }
};
