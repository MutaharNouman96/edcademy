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
        Schema::create('educator_payment_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('educator_id')->constrained('users')->onDelete('cascade');

            $table->string('currency')->default('USD');
            $table->string('schedule')->default('manual');
            $table->integer('min_threshold')->default(50);

            $table->string('billing_name')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('invoice_email')->nullable();

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
        Schema::dropIfExists('educator_payment_settings');
    }
};
