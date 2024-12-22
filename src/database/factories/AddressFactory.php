<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'zip' => $this->faker->regexify('\d{3}-\d{4}'),
            'address' => $this->faker->address,
            'building' => $this->faker->secondaryAddress,
        ];
    }
}