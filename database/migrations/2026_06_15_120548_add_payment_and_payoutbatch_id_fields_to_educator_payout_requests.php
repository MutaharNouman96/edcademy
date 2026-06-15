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
        Schema::table('educator_payout_requests', function (Blueprint $table) {
            //
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('no action');
            $table->foreignId('payout_batch_id')->nullable()->constrained('payout_batches')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('educator_payout_requests', function (Blueprint $table) {
            //
        });
    }
};
