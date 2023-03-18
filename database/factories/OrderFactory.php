<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "status" => 0,
            "email" => fake()->email(),
            "fname" => fake()->firstName(),
            "lname" => fake()->lastName(),
            "street" => fake()->streetName(),
            "building" => fake()->buildingNumber(),
            "floor" => fake()->numberBetween(1, 5),
            "apartment" => fake()->randomNumber(3),
            "additional_info" => null,
            "phone" => fake()->randomNumber(9),
            "payment_method" => 1,
            "shipping_fees" => fake()->randomNumber(2),
            "total_amount" => fake()->randomNumber(2),
            "order_id" => fake()->randomNumber(6),
        ];
    }
}
