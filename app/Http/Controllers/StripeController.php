<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class StripeController extends Controller
{
    /**
     * Step 1:
     * Create pending order → Create Stripe session → Redirect user to Stripe
     */
    public function createCheckout(Request $request)
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        $amount = $cartItems->sum('total');

        // 1. Create order with pending state
        $order = Order::create([
            'user_id' => $user->id,
            'payment_method' => 'stripe',
            'status' => 'pending',
        ]);
          

        // 2. Store items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item->item_id,
                'model' => $item->model,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'tax' => $item->tax,
                'total' => $item->total,
            ]);
        }

        // 3. Create stripe session
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $lineItems = [];
        foreach ($cartItems as $ci) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => intval($ci->total * 100),
                    'product_data' => [
                        'name' => $ci->model . ' #' . $ci->item_id,
                    ],
                ],
                'quantity' => 1
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
    public function success(Request $request)
    {
        if (! $request->session_id) {
            abort(404, "Invalid payment session.");
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Retrieve the session from stripe
        $session = StripeSession::retrieve($request->session_id);

        $order = Order::find($session->metadata->order_id);

        if (! $order) {
            abort(404, "Order not found.");
        }

        // Update order to paid
        $order->update([
            'status' => 'paid',
            'transaction_id' => $session->payment_intent,
            'payment_details' => json_encode($session),
        ]);

        // Clear cart
        Cart::where('user_id', $order->user_id)->delete();

        return view('payment.success', compact('order'));
    }


    /**
     * Step 3:
     * If user cancels on Stripe → return here.
     */
    public function cancel(Request $request)
    {
        $orderId = $request->order_id;

        if ($orderId) {
            Order::where('id', $orderId)->update(['status' => 'cancelled']);
        }

        return redirect('/cart')->with('error', 'Payment was cancelled.');
    }
}
