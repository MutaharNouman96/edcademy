<?php

namespace App\Http\Controllers;

use App\Mail\PaymentCancelledMail;
use App\Models\Order;
use App\Services\CheckoutService;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class StripeController extends Controller
{
    /**
     * Step 1:
     * Create pending order → Create Stripe session → Redirect user to Stripe
     */
    public function createCheckout(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'order_id' => 'required',
        ]);
        $order = Order::find($request->order_id);

        if ($order->user_id != $user->id) {
            return back()->with('error', 'You are not authorized to make this payment.');
        }

        $orderItems = $order->items()->get();

        if ($orderItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $lineItems = [];
        foreach ($orderItems as $ci) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => intval($ci->total * 100),
                    'product_data' => [
                        'name' => $ci->model . ' #' . $ci->item_id,
                    ],
                ],
                'quantity' => 1,
            ];
        }

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => $lineItems,
            'metadata' => [
                'order_id' => $order->id,
            ],
            'success_url' => url('/stripe/success?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => url('/stripe/cancel?order_id=' . $order->id),
        ]);

        return redirect($session->url);
    }

    /**
     * Step 2:
     * If payment succeeded → Stripe redirects here.
     * Verify session → update order as paid → clear cart → show receipt
     */
    public function success(Request $request, CheckoutService $checkoutService)
    {
        if (! $request->session_id) {
            abort(404, 'Invalid payment session.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = StripeSession::retrieve($request->session_id);

        $order = Order::find($session->metadata->order_id);

        if (! $order) {
            abort(404, 'Order not found.');
        }

        $checkoutService->completePaidOrder(
            $order,
            $session->payment_intent,
            'stripe:' . ($session->payment_method_types[0] ?? 'card'),
            $session,
            'stripe',
        );

        return redirect()->route('payment.success', $order->id);
    }

    /**
     * Step 3:
     * If user cancels on Stripe → return here.
     */
    public function cancel(Request $request)
    {
        $orderId = $request->order_id;

        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->update(['status' => 'cancelled']);

                try {
                    EmailService::send(
                        $order->user->email,
                        new PaymentCancelledMail($order),
                        'emails'
                    );
                } catch (\Exception $e) {
                    Log::error('Failed to send payment cancelled email: ' . $e->getMessage());
                }
            }
        }

        return redirect('/cart')->with('error', 'Payment was cancelled.');
    }
}
