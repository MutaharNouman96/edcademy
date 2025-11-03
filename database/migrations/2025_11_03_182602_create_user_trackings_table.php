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
        Schema::create('user_trackings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->string('page');
            $table->string('action');
            $table->longText('data')->nullable();
            $table->longText('description')->nullable();
            $table->longText('ip')->nullable();
            $table->longText('agent')->nullable();
            $table->longText('url')->nullable();
            $table->longText('referrer')->nullable();
            $table->longText('user_agent')->nullable();
            $table->longText('session_id')->nullable();

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
        Schema::dropIfExists('user_trackings');
    }
};
