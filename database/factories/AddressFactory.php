<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'customer_id' => Customer::select('id')->inRandomOrder()->first()->id,
            'country' => fake()->country(),
            'address' => fake()->address(),
            'building_no' => fake()->numberBetween(1, 3),
            'city' => fake()->city()
        ];
    }
}
