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

    /**
     * After verifying their email, educators who have not finished Stripe
     * payout onboarding are sent straight to the setup screen — it is mandatory
     * before they can use the educator panel.
     */
    private function redirectAfterVerification($user): RedirectResponse
    {
        if ($user->isEducator() && ! $user->canReceivePayouts()) {
            return redirect()
                ->route('educator.settings', ['stripe' => 'required'])
                ->with('warning', 'Your email is verified. Please complete your Stripe payout setup (add your IBAN) to finish onboarding.');
        }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }
}
