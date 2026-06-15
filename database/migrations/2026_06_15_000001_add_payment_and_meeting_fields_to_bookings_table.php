<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Extends the bookings table so a booking can carry its full payment lifecycle
 * (Stripe checkout) and its generated online-meeting (Zoom) details, plus the
 * timestamp used to make the 30-minute reminder idempotent.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // --- Pricing / payment -------------------------------------------------
            $table->decimal('amount', 10, 2)->nullable()->after('subject');
            $table->string('currency', 10)->default('USD')->after('amount');
            // unpaid | paid | refunded | failed
            $table->string('payment_status')->default('unpaid')->after('currency');
            $table->string('stripe_session_id')->nullable()->after('payment_status');
            $table->string('payment_intent_id')->nullable()->after('stripe_session_id');
            $table->string('payment_method')->nullable()->after('payment_intent_id');
            $table->text('payment_details')->nullable()->after('payment_method');
            $table->timestamp('paid_at')->nullable()->after('payment_details');

            // --- Online meeting (Zoom) --------------------------------------------
            $table->string('platform')->default('Zoom')->after('paid_at');
            $table->string('meeting_link')->nullable()->after('platform');
            $table->string('meeting_id')->nullable()->after('meeting_link');
            $table->string('meeting_password')->nullable()->after('meeting_id');

            // --- Reminder bookkeeping ---------------------------------------------
            $table->timestamp('reminder_sent_at')->nullable()->after('meeting_password');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'amount',
                'currency',
                'payment_status',
                'stripe_session_id',
                'payment_intent_id',
                'payment_method',
                'payment_details',
                'paid_at',
                'platform',
                'meeting_link',
                'meeting_id',
                'meeting_password',
                'reminder_sent_at',
            ]);
        });
    }
};
