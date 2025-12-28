<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [

            // =========================
            // TAX SETTINGS
            // =========================
            [
                'key' => 'tax_enabled',
                'value' => '1',
                'type' => 'bool',
                'group' => 'tax',
                'description' => 'Enable or disable tax calculation platform-wide'
            ],
            [
                'key' => 'tax_percentage',
                'value' => '5',
                'type' => 'float',
                'group' => 'tax',
                'description' => 'Default VAT percentage applied to orders'
            ],
            [
                'key' => 'tax_inclusive',
                'value' => '0',
                'type' => 'bool',
                'group' => 'tax',
                'description' => 'Is tax included in price or added on top'
            ],

            // =========================
            // PLATFORM COMMISSION
            // =========================
            [
                'key' => 'platform_commission_default',
                'value' => '15',
                'type' => 'float',
                'group' => 'commission',
                'description' => 'Default platform commission percentage'
            ],
            [
                'key' => 'educator_basic_commission',
                'value' => '30',
                'type' => 'float',
                'group' => 'commission',
                'description' => 'Commission percentage for Basic educators'
            ],
            [
                'key' => 'educator_premium_commission',
                'value' => '15',
                'type' => 'float',
                'group' => 'commission',
                'description' => 'Commission percentage for Premium educators'
            ],
            [
                'key' => 'premium_subscription_price',
                'value' => '59',
                'type' => 'float',
                'group' => 'commission',
                'description' => 'Annual subscription price for Premium educators'
            ],

            // =========================
            // PAYOUT SETTINGS
            // =========================
            [
                'key' => 'minimum_payout_amount',
                'value' => '50',
                'type' => 'float',
                'group' => 'payout',
                'description' => 'Minimum balance required for educator payout'
            ],
            [
                'key' => 'payout_cycle',
                'value' => 'monthly',
                'type' => 'string',
                'group' => 'payout',
                'description' => 'Payout frequency: weekly, bi-weekly, monthly'
            ],
            [
                'key' => 'payout_day_of_month',
                'value' => '5',
                'type' => 'int',
                'group' => 'payout',
                'description' => 'Day of the month payouts are processed'
            ],
            [
                'key' => 'payout_processing_days',
                'value' => '3',
                'type' => 'int',
                'group' => 'payout',
                'description' => 'Processing delay before payout is released'
            ],

            // =========================
            // PAYMENT GATEWAYS
            // =========================
            [
                'key' => 'stripe_enabled',
                'value' => '1',
                'type' => 'bool',
                'group' => 'payment',
                'description' => 'Enable Stripe payments'
            ],
            [
                'key' => 'paypal_enabled',
                'value' => '1',
                'type' => 'bool',
                'group' => 'payment',
                'description' => 'Enable PayPal payments'
            ],
            [
                'key' => 'default_currency',
                'value' => 'USD',
                'type' => 'string',
                'group' => 'payment',
                'description' => 'Default platform currency'
            ],

            // =========================
            // REFUND & DISPUTES
            // =========================
            [
                'key' => 'refund_window_days',
                'value' => '7',
                'type' => 'int',
                'group' => 'refund',
                'description' => 'Number of days user can request refund'
            ],
            [
                'key' => 'refund_allowed_after_view',
                'value' => '0',
                'type' => 'bool',
                'group' => 'refund',
                'description' => 'Allow refunds after content is accessed'
            ],

            // =========================
            // COURSE & CONTENT
            // =========================
            [
                'key' => 'course_approval_required',
                'value' => '1',
                'type' => 'bool',
                'group' => 'content',
                'description' => 'Admin approval required before course is published'
            ],
            [
                'key' => 'max_course_price',
                'value' => '999',
                'type' => 'float',
                'group' => 'content',
                'description' => 'Maximum price allowed for a course'
            ],
            [
                'key' => 'free_course_limit',
                'value' => '3',
                'type' => 'int',
                'group' => 'content',
                'description' => 'Maximum number of free courses per educator'
            ],

            // =========================
            // SYSTEM & GENERAL
            // =========================
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'bool',
                'group' => 'system',
                'description' => 'Enable maintenance mode'
            ],
            [
                'key' => 'support_email',
                'value' => 'support@edcademy.com',
                'type' => 'string',
                'group' => 'system',
                'description' => 'Official support email address'
            ],
            [
                'key' => 'platform_name',
                'value' => 'Ed-Cademy',
                'type' => 'string',
                'group' => 'system',
                'description' => 'Platform display name'
            ],
        ];

        DB::table('application_settings')->insert($settings);
    }
}
