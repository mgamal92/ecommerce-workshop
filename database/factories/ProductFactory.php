<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

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
    public function definition()
    {
        return [
            'name' => fake()->text(10),
            'quantity' => fake()->numberBetween(1, 20),
            'category_id' => Category::select('id')->inRandomOrder()->first()->id,
            'price' => fake()->randomFloat(1, 20, 30)
        ];
    }
}
