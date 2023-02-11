<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
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
            'queue_number'=>fake()->unique()->randomNumber(9),
            'gender' => fake()->randomElement($array = ['Male', 'Female']),
            'birthday' => fake()->date(),
            'phone_number' => fake()->randomDigit(9),
            'category' => fake()->randomElement($array = ['Dental', 'Check up', 'Medical', 'other']),
            'specification' => fake()->randomElement($array = ['Infant', 'Child', 'Teen', 'Adult', 'Senior']),
            'date' => fake()->dateTimeBetween($startDate = '-1 month', $endDate = now(), $timezone = null),
            'status' => fake()->randomElement($array = ['Approved']),
            'time' => fake()->randomElement($array = ['AM','PM']),
            'user_id' => fake()->randomElement($array = [6,7,8]),
            'doctor_id' => fake()->randomElement($array = [3,4,5]),
        ];
    }
}
