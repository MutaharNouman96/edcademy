<?php

namespace App\Jobs;

use App\Mail\SessionReminderMail;
use App\Models\Booking;
use App\Services\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Sends the "your session starts soon" reminder to both the student and the
 * educator. The job is dispatched with a delay so it executes
 * BookingService::REMINDER_LEAD_MINUTES (30) minutes before the session start.
 *
 * It is idempotent: it bails out if the booking was cancelled, is no longer
 * paid, or a reminder has already been sent.
 */
class SendSessionReminderJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $timeout = 60;

    public function __construct(
        public int $bookingId
    ) {
    }

    public function handle(): void
    {
        $booking = Booking::with(['student', 'educator'])->find($this->bookingId);

        if (! $booking) {
            return;
        }

        // Don't remind for cancelled / unpaid sessions, and never twice.
        if ($booking->status === Booking::STATUS_CANCELLED
            || ! $booking->isPaid()
            || $booking->reminder_sent_at) {
            return;
        }

        try {
            if ($booking->student && $booking->student->email) {
                EmailService::send($booking->student->email, new SessionReminderMail($booking, true), 'emails');
            }
            if ($booking->educator && $booking->educator->email) {
                EmailService::send($booking->educator->email, new SessionReminderMail($booking, false), 'emails');
            }

            $booking->update(['reminder_sent_at' => now()]);
        } catch (\Throwable $e) {
            Log::error('Failed to send session reminder for booking #' . $booking->id, [
                'error' => $e->getMessage(),
            ]);

            throw $e; // allow the queue to retry
        }
    }
}
