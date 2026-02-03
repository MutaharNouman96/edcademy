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
use App\Services\EmailService;
use App\Mail\StudentWelcomeMail;
use App\Mail\AdminNotificationMail;

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

        // Send welcome email to student
        try {
            EmailService::send(
                $user->email,
                new StudentWelcomeMail($user),
                'emails'
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send student welcome email: ' . $e->getMessage());
        }

        // Send notification to admin about new student registration
        try {
            $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
            foreach ($adminEmails as $adminEmail) {
                EmailService::send(
                    $adminEmail,
                    new AdminNotificationMail(
                        'info',
                        [
                            'user_name' => $user->full_name,
                            'user_email' => $user->email,
                            'user_type' => 'Student',
                            'registration_date' => $user->created_at->format('M j, Y g:i A'),
                            'account_type' => $request->signupType === 'kid' ? 'Minor (with guardian)' : 'Adult',
                        ],
                        'New Student Registration - Ed-Cademy',
                        'A new student has registered on the platform.'
                    ),
                    'emails'
                );
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send admin notification for student registration: ' . $e->getMessage());
        }

        event(new Registered($user));

        Auth::login($user);

        if ($user->isStudent()) {
            return redirect(route('student.dashboard'));
        }

        return redirect(RouteServiceProvider::HOME);
    }
}
