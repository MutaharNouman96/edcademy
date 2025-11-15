<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Guardian;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'signupType' => ['required', 'string', 'in:self,kid'],
        ]);

        if ($request->signupType === 'kid') {
            $request->validate([
                'guardian_name' => ['required', 'string', 'max:255'],
                'guardian_relation' => ['required', 'string', 'max:255'],
                'guardian_contact' => ['required', 'string', 'max:255'],
            ]);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        if ($request->signupType === 'kid') {
            $user->guardian()->create([
                'guardian_name' => $request->guardian_name,
                'guardian_contact' => $request->guardian_contact,
                'guardian_relation' => $request->guardian_relation,
                'is_verified' => false, // Default to false, can be changed later
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        if ($user->isStudent()) {
            return redirect(route('student.dashboard'));
        }

        return redirect(RouteServiceProvider::HOME);
    }
}
