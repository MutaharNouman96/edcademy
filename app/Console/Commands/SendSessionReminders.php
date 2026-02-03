<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Services\EmailService;
use App\Mail\SessionReminderMail;
use Carbon\Carbon;

class SendSessionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for sessions scheduled in 24 hours';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Sending session reminders...');

        // Find sessions scheduled for tomorrow (24 hours from now)
        $tomorrow = Carbon::tomorrow();
        $sessionsTomorrow = Booking::where('status', 'confirmed')
            ->where('date', $tomorrow->toDateString())
            ->whereHas('student')
            ->whereHas('educator')
            ->get();

        $this->info("Found {$sessionsTomorrow->count()} sessions scheduled for tomorrow");

        $sentCount = 0;

        foreach ($sessionsTomorrow as $booking) {
            try {
                // Send reminder to student
                EmailService::send(
                    $booking->student->email,
                    new SessionReminderMail($booking, false), // false = for student
                    'emails'
                );

                // Send reminder to educator
                EmailService::send(
                    $booking->educator->email,
                    new SessionReminderMail($booking, true), // true = for educator
                    'emails'
                );

                $sentCount += 2; // Count both emails sent
                $this->line("Sent reminders for session: {$booking->subject} at {$booking->time}");

            } catch (\Exception $e) {
                $this->error("Failed to send reminder for session ID {$booking->id}: " . $e->getMessage());
            }
        }

        $this->info("Successfully sent {$sentCount} reminder emails");
        return Command::SUCCESS;
    }
}
