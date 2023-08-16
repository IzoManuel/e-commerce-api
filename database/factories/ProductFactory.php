<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $name = fake()->unique()->words(fake()->numberBetween(1, 2), true);
        $name = ucwords($name);
        //$name = fake()->unique()->word;
        $slug = Str::slug($name);
        return [
            'user_id' => 1,
            'slug' => $slug,
            'name' => $name,
            'category_id' => fake()->numberBetween(1, 6),
            'unit_price' => fake()->numberBetween(200, 3000),
            'description' => fake()->paragraphs(4, true),
            'min_quantity' => 1,
            'discount' => fake()->numberBetween(1, 200), // Adjust the upper limit as needed
            'current_stock' => 20,
        ];
    }
}