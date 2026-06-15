<?php

namespace App\Services;

use App\Models\Booking;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

/**
 * Handles the Stripe Checkout side of the booking flow: turning a pending
 * booking into a hosted Stripe Checkout session and later retrieving that
 * session to confirm payment.
 */
class BookingPaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret') ?: env('STRIPE_SECRET'));
    }

    /**
     * Create a Stripe Checkout session for the booking and return its URL.
     */
    public function createCheckoutUrl(Booking $booking): string
    {
        $educatorName = optional($booking->educator)->full_name ?? 'Educator';
        $amountCents  = (int) round(((float) $booking->amount) * 100);

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'mode'                 => 'payment',
            'customer_email'       => optional($booking->student)->email,
            'line_items'           => [[
                'price_data' => [
                    'currency'     => strtolower($booking->currency ?: 'usd'),
                    'unit_amount'  => $amountCents,
                    'product_data' => [
                        'name'        => sprintf('1-on-1 Session with %s', $educatorName),
                        'description' => sprintf(
                            '%s · %s · %s hour(s)',
                            $booking->subject,
                            $booking->scheduled_at->format('M j, Y g:i A'),
                            rtrim(rtrim((string) $booking->duration, '0'), '.')
                        ),
                    ],
                ],
                'quantity' => 1,
            ]],
            'metadata' => [
                'booking_id' => $booking->id,
                'type'       => 'session_booking',
            ],
            'success_url' => url('/booking/payment/success?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url'  => url('/booking/payment/cancel?booking_id=' . $booking->id),
        ]);

        // Keep a reference so we can reconcile if the user never returns.
        $booking->update(['stripe_session_id' => $session->id]);

        return $session->url;
    }

    /**
     * Retrieve a completed Stripe Checkout session.
     */
    public function retrieveSession(string $sessionId): StripeSession
    {
        return StripeSession::retrieve($sessionId);
    }
}
