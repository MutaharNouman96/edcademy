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
        Schema::create('payout_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('educator_id')->constrained('users')->onDelete('no action');
            $table->string("payment_ids")->nullable(); // comma separated list of payment ids
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled', 'processing'])->default('pending');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('total_commission', 10, 2)->default(0);
            $table->decimal('total_net_amount', 10, 2)->default(0);
            $table->string('currency')->default('USD');
            $table->longText('notes')->nullable();
            $table->longText("description")->nullable();
            $table->longText("stripe_response")->nullable();
            $table->string('processed_by')->nullable();
            $table->dateTime('processed_at')->nullable();
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
        Schema::dropIfExists('payout_batches');
    }
};
