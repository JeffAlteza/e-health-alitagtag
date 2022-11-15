<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesSeeder::class,
            UsersSeeder::class,
            AppointmentSeeder::class,
            PatientRecordSeeder::class,
        ]);

        // Roles::factory()->create([
        //     'name' => 'Administrator',
        // ]);
        // Roles::factory()->create([
        //     'name' => 'Nurse',
        // ]);
        // Roles::factory()->create([
        //     'name' => 'Doctor',
        // ]);
        // Roles::factory()->create([
        //     'name' => 'Patient',
        // ]);

        // User::factory()->create([
        //     'name' => 'Administrator',
        //     'email' => 'admin@gmail.com',
        //     'role_id'=>'1',
        // ]);
        // User::factory()->create([
        //     'name' => 'Nurse',
        //     'email' => 'nurse@gmail.com',
        //     'role_id'=>'2',
        // ]);
        // User::factory()->create([
        //     'name' => 'Doctor Willie',
        //     'email' => 'doctorwillie@gmail.com',
        //     'role_id'=>'3',
        // ]);
        // User::factory()->create([
        //     'name' => 'Doctor Jose',
        //     'email' => 'doctorjose@gmail.com',
        //     'role_id'=>'3',
        // ]);
        // User::factory()->create([
        //     'name' => 'Patient',
        //     'email' => 'patient@gmail.com',
        //     'role_id'=>'4',
        // ]);
        // User::factory()->create([
        //     'name' => 'Patient2',
        //     'email' => 'patient2@gmail.com',
        //     'role_id'=>'4',
        // ]);
        // User::factory()->create([
        //     'name' => 'Patient3',
        //     'email' => 'patient3@gmail.com',
        //     'role_id'=>'4',
        // ]);
    }
}
