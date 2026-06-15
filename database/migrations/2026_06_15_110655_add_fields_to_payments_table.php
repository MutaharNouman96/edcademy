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
        Schema::table('payments', function (Blueprint $table) {
            //
            $table->boolean("is_payout_processed")->default(false);
            $table->foreignId('payout_batch_id')->nullable()->constrained('payout_batches')->onDelete('no action');
            $table->enum("payout_status", ['pending', 'processing', 'paid', 'failed'])->default('pending');
            $table->boolean("is_payout_requested")->default(false);
            $table->dateTime("payout_requested_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
};
