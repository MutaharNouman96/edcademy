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
        Schema::create('educator_security_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('educator_id')->constrained('users')->onDelete('cascade');

            $table->boolean('twofa_enabled')->default(false);
            $table->enum('twofa_method', ['app', 'sms'])->nullable();
            $table->string('twofa_phone')->nullable();


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
        Schema::dropIfExists('educator_security_settings');
    }
};
