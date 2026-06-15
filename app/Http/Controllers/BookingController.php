<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\ActivityNotificationService;
use App\Services\BookingPaymentService;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Drives the student → educator session-booking flow:
 *
 *   1. createCheckout(): validate the request, create a pending booking and
 *      hand back a Stripe Checkout URL.
 *   2. paymentSuccess(): Stripe redirect target. Confirms the booking, which
 *      creates the Zoom meeting, emails both parties and schedules the reminder.
 *   3. paymentCancel(): Stripe cancel target. Marks the booking cancelled.
 */
class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookings,
        private BookingPaymentService $payments
    ) {
    }

    /**
     * POST /book-session
     * Creates a pending booking and returns a Stripe Checkout URL (JSON).
     */
    public function createCheckout(Request $request)
    {
        $request->validate([
            'date'        => 'required|date|after_or_equal:today',
            'time'        => 'required|string',
            'duration'    => 'required|numeric|in:1,1.5,2,3',
            'subject'     => 'required|string|max:255',
            'educator_id' => 'required|exists:users,id',
            'message'     => 'nullable|string|max:1000',
        ]);

        if (! Auth::check()) {
            return response()->json([
                'success'      => false,
                'require_auth' => true,
                'message'      => 'Please log in to book a session.',
            ], 401);
        }

        $educatorId = (int) $request->educator_id;
        $time       = $this->bookings->normalizeTime($request->time);

        // Re-check availability server-side to prevent double bookings.
        if (! $this->bookings->isSlotAvailable($educatorId, $request->date, $time)) {
            return response()->json([
                'success' => false,
                'message' => 'This time slot is no longer available. Please choose another.',
            ], 422);
        }

        try {
            $booking = $this->bookings->createPendingBooking(Auth::user(), $request->only([
                'date', 'time', 'duration', 'subject', 'educator_id', 'message',
            ]));

            $checkoutUrl = $this->payments->createCheckoutUrl($booking);

            return response()->json([
                'success'      => true,
                'message'      => 'Redirecting to secure payment...',
                'checkout_url' => $checkoutUrl,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to start booking checkout', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'We could not start the payment. Please try again.',
            ], 500);
        }
    }

    /**
     * GET /booking/payment/success?session_id=...
     */
    public function paymentSuccess(Request $request)
    {
        if (! $request->session_id) {
            abort(404, 'Invalid payment session.');
        }

        try {
            $session = $this->payments->retrieveSession($request->session_id);
            $bookingId = $session->metadata->booking_id ?? null;
            $booking = $bookingId ? Booking::find($bookingId) : null;

            if (! $booking) {
                abort(404, 'Booking not found.');
            }

            if (($session->payment_status ?? null) !== 'paid') {
                return redirect()
                    ->route('web.educator.show', $booking->educator_id)
                    ->with('error', 'Payment was not completed. Please try again.');
            }

            $booking = $this->bookings->confirmPaidBooking($booking, [
                'stripe_session_id' => $session->id,
                'payment_intent_id' => $session->payment_intent,
                'payment_method'    => 'stripe:' . ($session->payment_method_types[0] ?? 'card'),
                'payment_details'   => $session->toArray(),
            ]);

            // Log the activity for the student (best-effort).
            try {
                if ($booking->student) {
                    ActivityNotificationService::logAndNotify(
                        $booking->student,
                        'book_session',
                        'Booking',
                        $booking->id,
                        "Session with " . optional($booking->educator)->full_name,
                        null,
                        [
                            'educator_id' => $booking->educator_id,
                            'date'        => $booking->date,
                            'time'        => $booking->time,
                            'duration'    => $booking->duration,
                            'subject'     => $booking->subject,
                            'status'      => $booking->status,
                            'amount'      => $booking->amount,
                        ],
                        "Booked & paid session with " . optional($booking->educator)->full_name
                            . " for {$booking->date} at {$booking->time}",
                        ['booking_id' => $booking->id]
                    );
                }
            } catch (\Throwable $e) {
                Log::warning('Booking activity log failed: ' . $e->getMessage());
            }

            return view('website.booking-success', compact('booking'));
        } catch (\Throwable $e) {
            Log::error('Booking payment confirmation failed', ['error' => $e->getMessage()]);
            abort(500, 'Could not confirm your booking. Please contact support.');
        }
    }

    /**
     * GET /booking/payment/cancel?booking_id=...
     */
    public function paymentCancel(Request $request)
    {
        $booking = $request->booking_id ? Booking::find($request->booking_id) : null;

        if ($booking && $booking->status === Booking::STATUS_PENDING_PAYMENT) {
            $booking->update([
                'status'         => Booking::STATUS_CANCELLED,
                'payment_status' => Booking::PAYMENT_FAILED,
            ]);
        }

        $educatorId = $booking->educator_id ?? null;

        return redirect()
            ->route('web.educator.show', $educatorId)
            ->with('error', 'Payment was cancelled. Your session was not booked.');
    }
}
