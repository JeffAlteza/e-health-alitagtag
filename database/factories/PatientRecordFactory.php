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
            'patient_number' => fake()->unique()->numberBetween(0001, 1000),
            'name' => fake()->name(),
            'address' => fake()->address(),
            'age' => fake()->numberBetween(1, 100),
            'weight' => fake()->numberBetween(1, 100),
            'birthday' => fake()->date(),
            'phone_number' => fake()->phoneNumber(),
            'time_of_consultation' => fake()->time(),
            'date_of_consultation' => fake()->dateTimeBetween($startDate = '-11 month', $endDate = now(), $timezone = null),
        ];
    }
}
