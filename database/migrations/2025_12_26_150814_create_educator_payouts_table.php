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
        Schema::create('educator_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('educator_id')->constrained('users')->onDelete('no action');
            $table->string('payment_id');

            $table->float('amount')->unsigned();
            $table->enum('status', ['pending', 'completed', 'failed'])->default("pending");
            $table->datetime('processed_at')->nullable();
            $table->string("processed_by")->nullable();
            $table->longText("description")->nullable();
            $table->boolean('acknowledged')->default(0);
            $table->string('invoice_link')->nullable();

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
        Schema::dropIfExists('educator_payouts');
    }
};
