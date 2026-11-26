<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Income;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Income>
 */
class IncomeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'title' => fake()->sentence(3),
            'amount' => fake()->randomFloat(2, 100000, 5000000),
            'received_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'source' => fake()->optional()->company(),
            'note' => fake()->optional()->sentence(),
        ];
    }
}
