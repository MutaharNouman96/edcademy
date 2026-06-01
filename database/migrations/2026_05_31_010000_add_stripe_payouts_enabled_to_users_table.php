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
        Schema::table('users', function (Blueprint $table) {
            // Becomes true once the educator finishes Stripe Connect onboarding
            // (identity + IBAN/bank submitted and payouts enabled by Stripe).
            $table->boolean('stripe_payouts_enabled')
                ->default(false)
                ->after('stripe_connect_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('stripe_payouts_enabled');
        });
    }
};
