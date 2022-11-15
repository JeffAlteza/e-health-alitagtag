<?php

namespace Database\Seeders;

use App\Models\PatientRecord;
use Illuminate\Database\Seeder;

class PatientRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PatientRecord::factory(200)->create();
    }
}
