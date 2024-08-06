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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id(); // Equivalent to `id SERIAL PRIMARY KEY`
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->date('birthday');
            $table->string('address', 255);
            $table->string('ward_id', 20);
            $table->string('district_id', 20);
            $table->string('province_id', 20);
            $table->timestamps(); // Equivalent to `created_at` and `updated_at`
            $table->softDeletes(); // Equivalent to `deleted_at`
            // Adding foreign key constraints
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('ward_id')->references('id')->on('wards');
            $table->foreign('district_id')->references('id')->on('districts');
            $table->foreign('province_id')->references('id')->on('provinces');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
