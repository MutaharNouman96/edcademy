<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Educator Payout Release Schedule
    |--------------------------------------------------------------------------
    |
    | Controls how often App\Jobs\ReleaseEducatorPayoutJob runs (see the
    | scheduler in app/Console/Kernel.php). Change the value below (or the
    | PAYOUT_SCHEDULE env var) to adjust the release cadence without touching
    | any code.
    |
    | Supported values:
    |   'every_two_minutes' - useful for local testing
    |   'hourly'
    |   'daily'
    |   'weekly'
    |   'twice_monthly'     - released on the two days configured below
    |   'monthly'
    |
    */
    'schedule' => env('PAYOUT_SCHEDULE', 'every_two_minutes'),

    // Days of the month used when 'schedule' is 'twice_monthly'.
    'twice_monthly_days' => [1, 16],

    // Day of the month used when 'schedule' is 'monthly'.
    'monthly_day' => 1,

    // Time of day (24h "HH:MM") at which the scheduled run fires.
    'run_at' => env('PAYOUT_RUN_AT', '00:00'),

    /*
    |--------------------------------------------------------------------------
    | Processing
    |--------------------------------------------------------------------------
    */

    // Label stored against each payout batch describing who processed it.
    'processor' => env('PAYOUT_PROCESSOR', 'stripe'),

    // Only payments with this status are eligible to be paid out.
    'eligible_payment_status' => 'approved',

    /*
    |--------------------------------------------------------------------------
    | Admin-approved payout requests
    |--------------------------------------------------------------------------
    |
    | When an admin approves an EducatorPayoutRequest the release job is queued
    | with this delay (minutes) before funds are transferred.
    |
    */
    'approval_delay_minutes' => (int) env('PAYOUT_APPROVAL_DELAY_MINUTES', 2),

    // Admin inbox for payout-processed notifications (falls back to ADMIN_EMAIL).
    'admin_notification_email' => env('PAYOUT_ADMIN_EMAIL', env('ADMIN_EMAIL')),

];
