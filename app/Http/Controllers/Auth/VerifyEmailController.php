<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return $this->redirectAfterVerification($user);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return $this->redirectAfterVerification($user);
    }

    private function redirectAfterVerification($user): RedirectResponse
    {
        if ($user->isEducator()) {
            return redirect()
                ->route('educator.dashboard')
                ->with('success', 'Your email is verified. You can start adding courses right away. Complete payout setup when you are ready to receive earnings.');
        }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }
}
