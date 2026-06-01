<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Forces educators to finish Stripe Connect onboarding before they can use the
 * educator panel. Educators who have not connected (and had payouts enabled by
 * Stripe) are redirected to their settings page to complete the setup.
 *
 * The settings page and the Stripe Connect onboarding routes must be excluded
 * from this middleware so the educator has somewhere to actually complete it.
 */
class EnsureEducatorStripeConnected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Only gate educators; other roles pass straight through.
        if (! $user || ! $user->isEducator() || $user->canReceivePayouts()) {
            return $next($request);
        }

        $message = 'Please complete your Stripe payout setup (add your IBAN/bank details) before continuing.';

        // For AJAX/JSON endpoints return a 403 so front-end calls fail clearly
        // instead of receiving an unexpected HTML redirect.
        if ($request->expectsJson()) {
            return response()->json([
                'message'           => $message,
                'stripe_setup_url'  => route('educator.settings', ['stripe' => 'required']),
            ], 403);
        }

        return redirect()
            ->route('educator.settings', ['stripe' => 'required'])
            ->with('error', $message);
    }
}
