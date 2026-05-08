<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'category'    => $this->faker->randomElement(['食費', '交通費', '日用品', '趣味', '外食']),
            'amount'      => $this->faker->numberBetween(100, 50000),
            'description' => $this->faker->optional()->sentence(),
            'spent_at'    => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'image_path'  => null,
        ];
    }
}
