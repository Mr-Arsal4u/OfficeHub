<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::first(), // link to Employee factory
            'date' => $this->faker->date,
            'status' => $this->faker->randomElement(['Present']),
            'check_in_time' => $this->faker->time(),
            'check_out_time' => $this->faker->time(),
        ];
    }
}
