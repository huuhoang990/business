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
        Schema::create('districts', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name', 255);
            $table->string('name_en', 255)->nullable();
            $table->string('full_name', 255)->nullable();
            $table->string('full_name_en', 255)->nullable();
            $table->string('code_name', 255)->nullable();
            $table->string('province_code', 20)->nullable();
            $table->foreign('province_code')->references('id')->on('provinces');
            $table->integer('administrative_unit_id')->nullable();
            $table->foreign('administrative_unit_id')->references('id')->on('administrative_units');
        });

        // Creating index within the same up() method, after table creation
        Schema::table('districts', function (Blueprint $table) {
            $table->index('province_code', 'idx_districts_province');
            $table->index('administrative_unit_id', 'idx_districts_unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
