<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Roles::factory()->create([
            'name' => 'Administrator'
        ]);
        Roles::factory()->create([
            'name' => 'Nurse',
        ]);
        Roles::factory()->create([
            'name' => 'Doctor',
        ]);
        Roles::factory()->create([
            'name' => 'Patient',
        ]);
    }
}
