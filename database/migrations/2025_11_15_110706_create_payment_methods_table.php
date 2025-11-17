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
        Schema::create('educator_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('educator_id')->constrained('users')->onDelete('cascade');

            $table->enum('type', ['bank', 'paypal', 'wise', 'stripe']);
            $table->string('label');
            $table->text('details'); // encrypted JSON or text
            $table->boolean('is_default')->default(false);

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
        Schema::dropIfExists('educator_payment_methods');
    }
};
