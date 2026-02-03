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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_role'); // admin, educator, student
            $table->string('action_type'); // create, update, delete, approve, reject, etc.
            $table->string('entity_type'); // Course, User, Booking, Payment, etc.
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('entity_name')->nullable(); // Human readable name
            $table->json('old_values')->nullable(); // Previous state for updates
            $table->json('new_values')->nullable(); // New state for updates
            $table->text('description'); // Human readable description
            $table->json('metadata')->nullable(); // Additional context data
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['entity_type', 'entity_id']);
            $table->index(['action_type', 'created_at']);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
};
