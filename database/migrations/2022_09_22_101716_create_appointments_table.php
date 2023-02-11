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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('queue_number')->unique();
            $table->string('name');
            $table->string('gender');
            $table->date('birthday');
            $table->string('phone_number');
            $table->string('category');
            $table->string('specification');
            $table->string('time');
            $table->date('date');
            $table->string('status')->default('Pending');
            $table->string('cancelation_reason')->nullable();
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
        Schema::dropIfExists('appointments');
    }
};
