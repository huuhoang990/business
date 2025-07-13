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
        Schema::create('products', function (Blueprint $table) {
            $table->string('product_id', 20)->primary();
            $table->string('product_name', 255);
            $table->string('product_image', 255)->nullable();
            $table->decimal('product_price', 15, 2);
            $table->tinyInteger('is_sales')->default(1)
                ->comment('0: Dừng bán hoặc dừng sản xuất, 1: Còn hàng');
            $table->text('description')->nullable()->comment('Mô tả');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
