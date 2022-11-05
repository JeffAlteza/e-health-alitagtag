<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PatientRecord>
 */
class PatientRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'name' => fake()->name(),
            'address' => fake()->address(),
            'age' => fake()->numberBetween(1,100),
            'time_of_consultation'=>fake()->time(),
            'date_of_consultation'=>fake()->dateTimeBetween($startDate = '-10 month', $endDate = '+2 month', $timezone = null),
        ];
    }
}
