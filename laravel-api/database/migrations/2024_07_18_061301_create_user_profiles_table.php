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
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->date('birthday');
            $table->string('address', 255);
            $table->integer('commune_id');
            $table->integer('district_id');
            $table->integer('province_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('commune_id')->references('id')->on('master_communes');
            $table->foreign('district_id')->references('id')->on('master_districts');
            $table->foreign('province_id')->references('id')->on('master_provinces');
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
