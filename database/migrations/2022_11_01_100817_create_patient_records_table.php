<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_records', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->integer('age');
            $table->date('date_of_consultation');
            $table->string('time_of_consultation');
            $table->string('nature_of_visit');
            $table->string('purpose_of_visit');
            $table->string('BP');
            $table->string('RR');
            $table->string('PR');
            $table->string('HC');
            $table->string('AC');
            $table->string('temp');
            $table->string('height');
            $table->string('weight');
            $table->string('LMP');
            $table->string('FHT');
            $table->string('EDC');
            $table->string('AOG');
            $table->string('FUNDIC_HT');
            $table->string('WAIST_CIR');
            $table->boolean('smoker');
            $table->boolean('alcohol_drinker');
            $table->string('chief_complaint');
            $table->string('recommendation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_records');
    }
};
