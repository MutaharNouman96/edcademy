<?php

namespace App\Services;

use App\Jobs\SendSessionReminderJob;
use App\Mail\SessionBookedMail;
use App\Models\Booking;
use App\Models\EducatorProfile;
use App\Models\EducatorSessionSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Core domain service for the session-booking module.
 *
 * Responsibilities:
 *  - Compute available time slots for an educator (respecting their schedule,
 *    max sessions per day and existing bookings).
 *  - Create a "pending payment" booking before sending the student to Stripe.
 *  - Confirm a booking once payment succeeds: generate the Zoom meeting, persist
 *    payment details, notify both parties and schedule the reminder.
 */
class BookingService
{
    /** How many minutes before the session the reminder should fire. */
    public const REMINDER_LEAD_MINUTES = 30;

    public function __construct(
        private ZoomService $zoom
    ) {
    }

    // -------------------------------------------------------------------------
    // Availability
    // -------------------------------------------------------------------------

    /**
     * Available time slots for an educator on a given date.
     *
     * @return array<int, string> e.g. ['09:00', '09:30', ...]
     */
    public function getAvailableSlots(int $educatorId, string $date, int $slotIntervalMinutes = 30): array
    {
        $carbonDate = Carbon::parse($date);
        $dayOfWeek  = $carbonDate->isoWeekday(); // 1=Mon .. 7=Sun

        $schedules = EducatorSessionSchedule::where('educator_id', $educatorId)
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time')
            ->get();

        if ($schedules->isEmpty()) {
            return [];
        }

        $profile  = EducatorProfile::where('user_id', $educatorId)->first();
        $maxPerDay = $profile && $profile->max_sessions_per_day ? (int) $profile->max_sessions_per_day : 6;

        // Existing bookings that still hold a slot (anything not cancelled).
        $bookings = Booking::where('educator_id', $educatorId)
            ->whereDate('date', $date)
            ->whereNotIn('status', [Booking::STATUS_CANCELLED])
            ->get();

        $existingCount = $bookings->count();
        if ($existingCount >= $maxPerDay) {
            return [];
        }
        $slotsAllowed = $maxPerDay - $existingCount;

        $minDurationHours = 1;
        $candidates = [];

        foreach ($schedules as $schedule) {
            $start = $this->timeStringToMinutes($schedule->start_time);
            $end   = $this->timeStringToMinutes($schedule->end_time);
            $minEnd = $start + (int) ($minDurationHours * 60);
            if ($minEnd > $end) {
                continue;
            }
            for ($t = $start; $t + (int) ($minDurationHours * 60) <= $end; $t += $slotIntervalMinutes) {
                $candidates[] = $this->minutesToTimeString($t);
            }
        }
        $candidates = array_unique($candidates);
        sort($candidates);

        $available = [];
        foreach ($candidates as $slotStart) {
            if (count($available) >= $slotsAllowed) {
                break;
            }
            $slotStartMin = $this->timeStringToMinutes($slotStart);
            $slotEndMin   = $slotStartMin + (int) ($minDurationHours * 60);

            $overlaps = false;
            foreach ($bookings as $b) {
                $bStart = $this->timeStringToMinutes($b->time);
                $bEnd   = $bStart + (float) $b->duration * 60;
                if ($slotStartMin < $bEnd && $slotEndMin > $bStart) {
                    $overlaps = true;
                    break;
                }
            }
            if (! $overlaps) {
                $available[] = $slotStart;
            }
        }

        return array_values($available);
    }

    /**
     * Dates in a month (Y-m) that have at least one available slot.
     *
     * @return array<int, string> e.g. ['2026-06-16', ...]
     */
    public function getAvailableDates(int $educatorId, string $month): array
    {
        $start = Carbon::parse($month . '-01');
        $end   = $start->copy()->endOfMonth();
        $today = Carbon::today();
        $dates = [];

        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if ($d->lt($today)) {
                continue;
            }
            if (count($this->getAvailableSlots($educatorId, $d->format('Y-m-d'))) > 0) {
                $dates[] = $d->format('Y-m-d');
            }
        }

        return $dates;
    }

    /**
     * Validate that a date + time is currently an available slot.
     */
    public function isSlotAvailable(int $educatorId, string $date, string $time): bool
    {
        $slots = $this->getAvailableSlots($educatorId, $date);

        return in_array($this->normalizeTime($time), $slots, true);
    }

    /**
     * Normalize an incoming time string to "HH:MM" (24h, zero-padded).
     */
    public function normalizeTime(string $time): string
    {
        $time = preg_replace('/^(\d{1,2}):(\d{2}).*$/', '${1}:${2}', $time);
        $time = substr($time, 0, 5);
        if (strlen($time) === 4) {
            $time = '0' . $time;
        }

        return $time;
    }

    // -------------------------------------------------------------------------
    // Booking lifecycle
    // -------------------------------------------------------------------------

    /**
     * Create a booking in the "pending_payment" state. The student is then sent
     * to Stripe Checkout; nothing else (Zoom meeting, emails, reminder) happens
     * until payment is confirmed.
     */
    public function createPendingBooking(User $student, array $data): Booking
    {
        $educator = User::findOrFail($data['educator_id']);
        $profile  = EducatorProfile::where('user_id', $educator->id)->first();

        $hourlyRate = (float) ($profile->hourly_rate ?? 0);
        $duration   = (float) $data['duration'];
        $amount     = round($hourlyRate * $duration, 2);

        return Booking::create([
            'student_id'     => $student->id,
            'educator_id'    => $educator->id,
            'date'           => $data['date'],
            'time'           => $this->normalizeTime($data['time']),
            'duration'       => $duration,
            'subject'        => $data['subject'],
            'message'        => $data['message'] ?? null,
            'status'         => Booking::STATUS_PENDING_PAYMENT,
            'amount'         => $amount,
            'currency'       => strtoupper(setting('currency', 'USD')),
            'payment_status' => Booking::PAYMENT_UNPAID,
            'platform'       => 'Zoom',
        ]);
    }

    /**
     * Confirm a booking after a successful payment:
     *  - persist payment details
     *  - create the Zoom meeting
     *  - email student + educator
     *  - schedule the 30-minute reminder
     *
     * Idempotent: if the booking is already paid it is returned untouched.
     */
    public function confirmPaidBooking(Booking $booking, array $payment = []): Booking
    {
        if ($booking->isPaid()) {
            return $booking;
        }

        // 1) Mark as paid / confirmed.
        $booking->fill([
            'status'            => Booking::STATUS_CONFIRMED,
            'payment_status'    => Booking::PAYMENT_PAID,
            'paid_at'           => now(),
            'stripe_session_id' => $payment['stripe_session_id'] ?? $booking->stripe_session_id,
            'payment_intent_id' => $payment['payment_intent_id'] ?? $booking->payment_intent_id,
            'payment_method'    => $payment['payment_method'] ?? $booking->payment_method,
            'payment_details'   => isset($payment['payment_details'])
                ? json_encode($payment['payment_details'])
                : $booking->payment_details,
        ]);
        $booking->save();

        // 2) Generate the Zoom meeting (graceful fallback when not configured).
        try {
            $meeting = $this->zoom->createMeetingForBooking($booking->fresh(['student', 'educator']));
            $booking->update([
                'platform'         => $meeting['platform'] ?? 'Zoom',
                'meeting_link'     => $meeting['join_url'] ?? null,
                'meeting_id'       => $meeting['id'] ?? null,
                'meeting_password' => $meeting['password'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to create meeting for booking #' . $booking->id, ['error' => $e->getMessage()]);
        }

        // 3) Notify both parties + schedule the reminder.
        $this->sendBookedEmails($booking->fresh(['student', 'educator']));
        $this->scheduleReminder($booking);

        return $booking->fresh(['student', 'educator']);
    }

    /**
     * Send the "session booked" confirmation email to both student and educator.
     */
    public function sendBookedEmails(Booking $booking): void
    {
        $student  = $booking->student;
        $educator = $booking->educator;

        try {
            if ($student && $student->email) {
                EmailService::send($student->email, new SessionBookedMail($booking, true), 'emails');
            }
            if ($educator && $educator->email) {
                EmailService::send($educator->email, new SessionBookedMail($booking, false), 'emails');
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send session booked emails for booking #' . $booking->id, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Schedule the reminder job to run REMINDER_LEAD_MINUTES before the session.
     * If that moment is already in the past, the job is dispatched immediately.
     */
    public function scheduleReminder(Booking $booking): void
    {
        $remindAt = $booking->scheduled_at->copy()->subMinutes(self::REMINDER_LEAD_MINUTES);

        $job = (new SendSessionReminderJob($booking->id))->onQueue('emails');

        if ($remindAt->isFuture()) {
            $job->delay($remindAt);
        }

        dispatch($job);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function timeStringToMinutes($time): int
    {
        if ($time instanceof Carbon) {
            return (int) $time->format('G') * 60 + (int) $time->format('i');
        }
        $parts = explode(':', substr($time, 0, 5));

        return ((int) ($parts[0] ?? 0)) * 60 + ((int) ($parts[1] ?? 0));
    }

    private function minutesToTimeString(int $minutes): string
    {
        return sprintf('%02d:%02d', (int) floor($minutes / 60), $minutes % 60);
    }
}
