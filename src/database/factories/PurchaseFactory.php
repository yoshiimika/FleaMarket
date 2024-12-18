<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'amount' => $this->faker->numberBetween(1000, 10000),
            'payment_method' => $this->faker->randomElement(['credit_card', 'convenience_store']),
            'shopping_zip' => $this->faker->postcode(),
            'shopping_address' => $this->faker->address(),
        ];
    }
}
