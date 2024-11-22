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
        return [
            'product_name' => fake()->word,
            'photo' => fake()->imageUrl(640, 480, 'products', true, 'Faker'),
            'description' => fake()->sentence,
            'price' => fake()->randomFloat(2,1000,100000),
            'stock' => fake()->randomFloat(2,1,1000),
            'discount' => fake()->randomFloat(2,0,100),
        ];
    }
}
