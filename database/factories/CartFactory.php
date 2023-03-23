<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'customer_id' => Customer::factory(),
            'products' => [
                [
                    'product_id' => Product::select('id')->inRandomOrder()->first()->id,
                    'quantity' => fake()->numberBetween(1, 20)
                ],
            ],
            'offer_sent' => 0,
        ];
    }
}
