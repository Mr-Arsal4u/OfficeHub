<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'product_name' => $this->faker->word,
            'quantity' => $this->faker->numberBetween(1, 100),
            'total' => $this->faker->randomFloat(2, 50, 2000),
            'date' => $this->faker->date,
        ];
    }
}
