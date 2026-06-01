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
        Schema::table('educator_payouts', function (Blueprint $table) {
            // Reference id of the Stripe transfer/payout used to release funds.
            $table->string('stripe_payout_id')->nullable()->after('processed_by');
            // Full raw payload returned by Stripe for auditing / reconciliation.
            $table->longText('stripe_response')->nullable()->after('stripe_payout_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('educator_payouts', function (Blueprint $table) {
            $table->dropColumn(['stripe_payout_id', 'stripe_response']);
        });
    }
};
