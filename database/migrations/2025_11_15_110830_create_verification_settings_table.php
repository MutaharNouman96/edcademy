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
        Schema::create('educator_verification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('educator_id')->constrained('users')->onDelete('cascade');

            $table->string('gov_id_file')->nullable();
            $table->string('credential_file')->nullable();
            $table->string('business_type')->default('individual');
            $table->boolean('tos')->default(false);

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
        Schema::dropIfExists('educator_verification_settings');
    }
};
