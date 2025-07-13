<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MstCustomer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    public function definition(): array
    {
        return [
            'customer_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'tel_num' => fake()->regexify('[0-9]{14}'),
            'address' => fake()->unique()->address(),
            'is_active' => fake()->randomElement([0, 1])
        ];
    }
}
