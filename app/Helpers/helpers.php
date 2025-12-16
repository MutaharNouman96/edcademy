<?php

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

if (! function_exists('cartTotalItems')) {
    function cartTotalItems()
    {
        // If user is logged in
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->count();
        }

        // If using session_id for guests
        $sessionId = session()->getId();

        return Cart::where('user_id', $sessionId)->count();
    }
}

//calculate cart total
if (! function_exists('cartTotal')) {
    function cartTotal()
    {
        return Cart::where('user_id', Auth::check() ? Auth::id() : session()->getId())->sum('total');
    }
}
//calculate tax of total
if (! function_exists('taxAmountOfPrice')) {
    function taxAmountOfPrice($price , $tax)
    {
        return round(($price * $tax) / 100 , 2);
    }
}
