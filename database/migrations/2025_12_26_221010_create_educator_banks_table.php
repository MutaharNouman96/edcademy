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
        Schema::create('educator_banks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('educator_id')->constrained('users')->onDelete('cascade');
            $table->string('bank_name');
            $table->string('account_name');
            $table->string('iban');
            $table->boolean('approval_status')->default(false);
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('educator_banks');
    }
};
