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
        Schema::create('wards', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name', 255);
            $table->string('name_en', 255)->nullable();
            $table->string('full_name', 255)->nullable();
            $table->string('full_name_en', 255)->nullable();
            $table->string('code_name', 255)->nullable();
            $table->string('district_code', 20)->nullable();
            $table->foreign('district_code')->references('id')->on('districts');
            $table->integer('administrative_unit_id')->nullable();
            $table->foreign('administrative_unit_id')->references('id')->on('administrative_units');
        });

        // Creating index within the same up() method, after table creation
        Schema::table('wards', function (Blueprint $table) {
            $table->index('district_code', 'idx_wards_district');
            $table->index('administrative_unit_id', 'idx_wards_unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wards');
    }
};
