<?php

namespace App\Console;

use App\Jobs\ReleaseEducatorPayoutJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Session reminders are now event-driven: a delayed SendSessionReminderJob
        // is queued when a booking is paid and fires 30 minutes before the
        // session start (see App\Services\BookingService::scheduleReminder).

        // Send weekly student reports every Monday at 10 AM
        $schedule->command('students:send-weekly-reports')
                 ->weeklyOn(1, '10:00') // Monday at 10:00 AM
                 ->withoutOverlapping()
                 ->runInBackground();

        // Send batched admin notifications every 12 hours.
        $schedule->command('admin:notifications-digest')
                 ->twiceDaily(0, 12)
                 ->withoutOverlapping()
                 ->runInBackground();

        // Release educator payouts on a configurable cadence. The frequency is
        // driven entirely by config/payout.php ('schedule' key) so the release
        // time can be changed (every 2 minutes, hourly, monthly, twice a
        // month, etc.) without editing this file.
        $this->scheduleEducatorPayouts($schedule);
    }

    /**
     * Register the educator payout job using the cadence configured in
     * config/payout.php. Defaults to twice a month when no/invalid value is set.
     */
    protected function scheduleEducatorPayouts(Schedule $schedule): void
    {
        $event = $schedule->job(new ReleaseEducatorPayoutJob())
                          ->withoutOverlapping();

        $time      = config('payout.run_at', '00:00');
        $twiceDays = config('payout.twice_monthly_days', [1, 16]);

        match (config('payout.schedule', 'twice_monthly')) {
            'every_two_minutes' => $event->everyTwoMinutes(),
            'hourly'            => $event->hourly(),
            'daily'             => $event->dailyAt($time),
            'weekly'            => $event->weeklyOn(1, $time),
            'monthly'           => $event->monthlyOn(config('payout.monthly_day', 1), $time),
            default             => $event->twiceMonthly($twiceDays[0] ?? 1, $twiceDays[1] ?? 16, $time),
        };
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
