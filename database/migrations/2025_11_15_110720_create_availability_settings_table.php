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
        Schema::create('educator_availability_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('educator_id')->constrained('users')->onDelete('cascade');

            $table->string('timezone')->nullable();
            $table->integer('default_length')->default(60);
            $table->integer('buffer')->default(0);
            $table->boolean('instant_booking')->default(false);

            $table->json('weekly_schedule')->nullable();  // mon→start/end
            $table->integer('lead_time')->default(24);
            $table->integer('max_per_day')->default(6);

            $table->boolean('vacation_mode')->default(false);
            $table->date('vacation_start')->nullable();
            $table->date('vacation_end')->nullable();
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
        Schema::dropIfExists('educator_availability_settings');
    }
};
