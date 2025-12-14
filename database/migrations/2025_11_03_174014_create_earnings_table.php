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
        Schema::create('earnings', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->unsignedBigInteger('educator_id');
            $table->unsignedBigInteger('payment_id')->nullable(); // link to Payment
            $table->unsignedBigInteger('payout_id')->nullable();  // link to Payout
            $table->unsignedBigInteger('session_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('course_resource_id')->nullable(); // e.g. lesson/worksheet

            // Amounts
            $table->decimal('gross_amount', 10, 2)->unsigned()->default(0);  // total before commission
            $table->decimal('platform_commission', 10, 2)->unsigned()->default(0); // platform fee
            $table->decimal('net_amount', 10, 2)->unsigned()->default(0);   // educator’s earning
            $table->string('currency', 10)->default('USD');

            // Meta
            $table->string('source_type')->nullable(); // 'session', 'course', 'resource'
            $table->enum('status', ['pending', 'approved', 'paid', 'cancelled'])->default('pending');
            $table->longText('description')->nullable();

            // Time tracking
            $table->timestamp('earned_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            // Foreign keys
            // $table->foreign('educator_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
            // $table->foreign('payout_id')->references('id')->on('payouts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('earnings');
    }
};
