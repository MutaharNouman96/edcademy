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
        Schema::create('educator_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('educator_id')->constrained('users')->onDelete('no action');

            $table->string("payment_id")->nullable();

            $table->decimal('gross_amount', 10, 2);     // total payment received
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('platform_commission', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2);       // amount payable to educator
            $table->string('currency', 10)->default('USD');

            $table->foreignId('order_id')->references('id')->on('orders');
            $table->foreignId('order_item_id')->references('id')->on('order_items');
            $table->enum('status', ['processing','pending', 'completed', 'refunded', 'failed'])->default('pending'); // pending, completed, refunded, failed

            $table->text('notes')->nullable();

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
        Schema::dropIfExists('educator_payments');
    }
};
