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
            'gender' => fake()->randomElement($array = ['Male','Female']),
            'birthday'=> fake()->date(),
            'phone_number'=>fake()->randomDigit(9),
            'category' => fake()->randomElement($array = ['Dental','Check up','Medical','other']),
            'specification' => fake()->randomElement($array = ['Infant','Child','Teen','Adult','Senior']),
            'date'=>fake()->dateTimeBetween($startDate = '-10 month', $endDate = '+2 month', $timezone = null),
            'status' => fake()->randomElement($array = ['Success','Pending','Cancelled']),
            'user_id' => fake()->randomElement($array = [4,5,6]),
            'doctor_id' => 3,
        ];
    }
}
