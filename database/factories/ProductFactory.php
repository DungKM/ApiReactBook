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
            'name' => fake()->name(),
            'price' => fake()->randomFloat(2, 10, 1000),
            'quantity' => fake()->numberBetween(1, 100),
            'quantity_page' => fake()->numberBetween(1, 10),
            'sale' => fake()->numberBetween(0, 50),
            'image' => fake()->imageUrl(),
            'description' => fake()->text(),
            'category_id' => fake()->numberBetween(1, 10), // Replace with your category IDs
            'brand_id' => fake()->numberBetween(1, 10), // Replace with your brand IDs
        ];
    }
}