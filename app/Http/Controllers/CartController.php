<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //validation
        $request->validate([
            'item_id' => 'required',
            'model' => 'required',
            'quantity' => 'required',
            'price' => 'required',
        ]);

        if (Auth::check()) {
            $userId = Auth::id();
        } else {
            $userId =  session()->getId();
        }

        $cart = new Cart();

        $cart->user_id = $userId;
        $cart->item_id = $request->item_id;
        $cart->model = $request->model;
        $cart->quantity = $request->quantity;
        $cart->price = $request->price;
        $cart->tax = 0;
        $cart->total = $request->price * $request->quantity;

        $cart->created_at = now();
        $cart->updated_at = now();

        $cart->save();

        return redirect()->route('web.cart')->with('success', 'Item added to cart');
    }


    public function checkout(Request $request)
    {
        $user = Auth::user();
        $method = $request->payment_method; // stripe | paypal

        $cart = Cart::where("user_id", $user->id)->get();
        if ($cart->isEmpty()) {
            return response()->json(["error" => "Cart is empty"], 422);
        }

        $amount = $cart->sum("total");

        // Create order first
        $order = Order::create([
            "user_id"        => $user->id,
            "payment_method" => $method,
            "status"         => "pending",
        ]);
      

        foreach ($cart as $c) {
            OrderItem::create([
                "order_id" => $order->id,
                "item_id"  => $c->item_id,
                "model"    => $c->model,
                "quantity" => $c->quantity,
                "price"    => $c->price,
                "tax"      => $c->tax,
                "total"    => $c->total,
            ]);
        }

        if ($method === "stripe") {
            return $this->stripeCheckout($order, $cart, $amount);
        }

        if ($method === "paypal") {
            return $this->paypalPayload($order, $amount);
        }

        return response()->json(["error" => "Invalid method"], 422);
    }



    protected function stripeCheckout($order, $cart, $amount)
    {
        Stripe::setApiKey(env("STRIPE_SECRET"));

        $lineItems = [];
        foreach ($cart as $item) {
            $lineItems[] = [
                "price_data" => [
                    "currency" => "usd",
                    "product_data" => [
                        "name" => $item->model . " ID: " . $item->item_id,
                    ],
                    "unit_amount" => intval($item->total * 100),
                ],
                "quantity" => $item->quantity
            ];
        }

        $session = StripeSession::create([
            "payment_method_types" => ["card"],
            "mode" => "payment",
            "line_items" => $lineItems,
            "metadata" => [
                "order_id" => $order->id
            ],
            "success_url" => url("cart/callback/payment/success?session_id={CHECKOUT_SESSION_ID}"),
            "cancel_url"  => url("/payment/cancel?order_id=" . $order->id),
        ]);

        return response()->json([
            "url"      => $session->url,
            "session"  => $session->id,
            "order_id" => $order->id
        ]);
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        //
    }


    public function loginUserInCartPage(Request $request)
    {
        $request->validate([
            "email"    => "required|email",
            "password" => "required",
        ]);

        // Try authentication
        if (!auth()->attempt($request->only("email", "password"))) {
            return response()->json([
                "status"  => "error",
                "login_message" => "Invalid email or password.",
            ], 401);
        }

        $user = auth()->user();
        // Fetch user cart now that user is authenticated
        $cart = Cart::where("user_id", session()->getId())->update([
            "user_id" => $user->id
        ]);

        return response()->json([
            "status" => "success",
            "login_message" => "Login successful.",
            "user"   => $user,
            "cart"   => $cart,
        ]);
    }
}
