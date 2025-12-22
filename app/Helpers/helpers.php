<?php


use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

if (! function_exists('cartTotalItems')) {
    function cartTotalItems()
    {
        $identifier = get_cart_identifier();

        // 1. Find the order first
        $order = Order::where('user_id', $identifier)
            ->where('status', 'cart')
            ->first();

        // 2. If no order exists, return 0. Otherwise, sum the quantities.
        if (!$order) {
            return 0;
        }

        return $order->items()->sum('quantity');
    }
}

//calculate cart total
if (! function_exists('cartTotal')) {
    function cartTotal()
    {
        $identifier = Auth::check() ? Auth::id() : Session::getId();

        // 1. Find the order first
        $order = Order::where('user_id', $identifier)
            ->where('status', 'cart')
            ->first();

        // 2. If no order exists, return 0. Otherwise, sum the quantities.
        if (!$order) {
            return 0;
        }

        return $order->items()->sum('total');
    }
}
//calculate tax of total
if (! function_exists('taxAmountOfPrice')) {
    function taxAmountOfPrice($price, $tax)
    {
        return round(($price * $tax) / 100, 2);
    }
}

if (!function_exists('get_cart_identifier')) {
    /**
     * Get a persistent identifier:
     * 1. Auth ID (if logged in)
     * 2. Persistent Cookie ID (if guest)
     */
    function get_cart_identifier()
    {
        // 1. If user is logged in, use their database ID
        if (Auth::check()) {
            return (string) Auth::id();
        }

        // 2. Check for an existing persistent guest cookie
        $guestId = Cookie::get('guest_cart_id');

        if (!$guestId) {
            // 3. If no cookie exists, check if there's one in the current Session
            // This handles the immediate request before the cookie is "sent" back
            $guestId = Session::get('guest_cart_id');
        }

        if (!$guestId) {
            // 4. Still nothing? Generate a new unique ID
            $guestId = Str::uuid()->toString();

            // Store in Session immediately for current request cycle
            Session::put('guest_cart_id', $guestId);

            // Queue a cookie to last for 30 days
            Cookie::queue('guest_cart_id', $guestId, 43200);
        }

        return $guestId;
    }
}
