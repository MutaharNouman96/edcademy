<?php

namespace App\Http\Controllers;

use App\Mail\OrderInvoiceMail;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Course;
use App\Models\EducatorPayment;
use App\Models\Lesson;
use App\Models\UserPurchasedItem;
use App\Services\EmailService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        // dd($order, $user);

        if ($order->user_id != $user->id) {
            return back()->with('error', 'You are not authorized to make this payment.');
        }

        $orderItems = $order->items()->get();

        if ($orderItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        $amount = $orderItems->sum('total');



        // 3. Create stripe session
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
            'payment_method' => "stripe:" . ($session->payment_method_types[0] ?? null),
        ]);

        $paymentId = $order->id . (Str::uuid());
        foreach ($order->items as $item) {
            $commission = money(
                $item->total * setting('platform_commission', 0) / 100
            );
            $itemEducatorId = $item->model == "App\Models\Lesson" ? Lesson::find($item->item_id)->with('course')->first()->course->user_id : Course::find($item->item_id)->user_id;
            UserPurchasedItem::firstOrCreate([
                'user_id' => auth()->id(),
                'purchasable_id' => $item->id,
                'purchasable_type' => $item->model,
                'active' => true,
            ]);
            EducatorPayment::firstOrCreate([
                'educator_id' => $itemEducatorId,
                'order_id' => $order->id,
                'order_item_id' => $item->id,
                'gross_amount' => $item->total,
                'currency' => setting('currency', 'USD'),
                'platform_commission' => $commission,
                'net_total' => $item->total - $commission,
                'payment_id' => $paymentId,
                'status' => 'completed',
            ]);
        }
        EmailService::send(
            $order->user->email,
            new OrderInvoiceMail($order),
            'emails'
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
            Order::where('id', $orderId)->update(['status' => 'cancelled']);
        }

        return redirect('/cart')->with('error', 'Payment was cancelled.');
    }
}
