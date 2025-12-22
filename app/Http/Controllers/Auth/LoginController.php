<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Events\Login;

class LoginController extends Controller
{
    //
    public function loginOrRegister(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // 1. Capture the Guest Session ID BEFORE the login session change
        $guestSessionId = Session::getId();

        // 2. Find or Create the User
        $user = User::firstOrCreate(
            ['email' => $request->email],
            ['password' => Hash::make($request->password)]
        );

        // 3. Log the user in
        Auth::login($user);

        // 4. Manually trigger the Login Event
        // This allows your OrderService (via the EventListener) to know who logged in
        event(new Login('web', $user, false));


        return response()->json([
            'message' => 'Authenticated successfully',
            'user' => $user,
            'order' => $user->orders()->where('status', 'cart')->first()
        ]);
    }
}
