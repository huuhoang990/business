<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productName = fake()->name();
        $firstLetter = strtoupper(substr($productName, 0, 1));
        $sequenceNumber = str_pad($this->faker->unique()->numberBetween(1, 999999999), 9, '0', STR_PAD_LEFT);
        $productId = $firstLetter . $sequenceNumber;
        return [
            'product_id' => $productId,
            'product_name' => $productName,
            'product_image' => fake()->imageUrl(640, 480, 'products', true),
            'product_price' => fake()->numberBetween(1000, 100000),
            'is_sales' => fake()->randomElement([0, 1]),
            'description' => fake()->text(200)
        ];
    }
}
