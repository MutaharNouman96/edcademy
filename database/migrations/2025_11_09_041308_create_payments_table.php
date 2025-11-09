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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('educator_id')->nullable(); // For payouts to educator
            $table->unsignedBigInteger('student_id')->nullable();  // For incoming student payments
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('course_resource_id')->nullable();

            $table->unsignedBigInteger('session_id')->nullable();

            $table->decimal('gross_amount', 10, 2);     // total payment received
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('platform_commission', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2);       // amount payable to educator
            $table->string('currency', 10)->default('USD');

            $table->string('payment_method')->nullable(); // Stripe, PayPal, etc.
            $table->string('transaction_id')->nullable();
            $table->string('status')->default('pending'); // pending, completed, refunded, failed

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('educator_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
