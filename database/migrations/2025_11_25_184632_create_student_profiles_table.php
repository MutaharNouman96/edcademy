<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();

            // Link to users table (assuming each student is a user)
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Profile Fields
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('pronouns')->nullable(); // He/Him, She/Her, They/Them
            $table->string('bio', 250)->nullable();
            $table->string('location')->nullable();
            $table->string('website')->nullable();
            $table->string('education_level')->nullable(); // University, High School etc.
            $table->string('interests')->nullable(); // Can store comma-separated values
            $table->string('email')->nullable(); // educator1@edcademy.com (or student email)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
