<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'role_id' => '1',
        ]);
        User::factory()->create([
            'name' => 'Nurse',
            'email' => 'nurse@gmail.com',
            'role_id' => '2',
        ]);
        
        User::factory()->create([
            'name' => 'Dra. Flordeliza V. Castillo',
            'email' => 'flordeliza_castillo@gmail.com',
            'Category'=>'Medical/Check Up',
            'role_id' => '3',
        ]);
        User::factory()->create([
            'name' => 'Dr. Melanie Tordecialla Amurao',
            'email' => 'melanie_amurao@gmail.com',
            'Category'=>'Dental',
            'role_id' => '3',
        ]);
        User::factory()->create([
            'name' => 'Dr. Rochelle Punzalan',
            'email' => 'rochelle_punzalan@gmail.com',
            'Category'=>'OB',
            'role_id' => '3',
        ]);
        
        User::factory()->create([
            'name' => 'Patient',
            'email' => 'patient@gmail.com',
            'role_id' => '4',
        ]);
        User::factory()->create([
            'name' => 'Patient2',
            'email' => 'patient2@gmail.com',
            'role_id' => '4',
        ]);
        User::factory()->create([
            'name' => 'Patient3',
            'email' => 'patient3@gmail.com',
            'role_id' => '4',
        ]);
    }
}
