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
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->integer('age')->nullable();
            $table->date('date_of_consultation')->nullable();
            $table->string('time_of_consultation')->nullable();
            $table->string('nature_of_visit')->nullable();
            $table->string('purpose_of_visit')->nullable();
            $table->string('BP')->nullable();
            $table->string('RR')->nullable();
            $table->string('PR')->nullable();
            $table->string('HC')->nullable();
            $table->string('AC')->nullable();
            $table->string('temp')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('LMP')->nullable();
            $table->string('FHT')->nullable();
            $table->string('EDC')->nullable();
            $table->string('AOG')->nullable();
            $table->string('FUNDIC_HT')->nullable();
            $table->string('WAIST_CIR')->nullable();
            $table->boolean('smoker')->nullable();
            $table->boolean('alcohol_drinker')->nullable();
            $table->string('chief_complaint')->nullable();
            $table->string('recommendation')->nullable();
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
