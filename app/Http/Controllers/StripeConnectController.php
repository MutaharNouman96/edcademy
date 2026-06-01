<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

/**
 * Handles the Stripe Connect "Express" marketplace lifecycle:
 *  - Vendor (educator) onboarding via Account Links.
 *  - Destination-charge checkout where the platform keeps an application fee
 *    and instantly transfers the remainder to the connected account.
 */
class StripeConnectController extends Controller
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Step 1 — Onboarding.
     *
     * Ensure the authenticated user has an Express connected account, then send
     * them through Stripe's hosted onboarding flow via an Account Link.
     */
    public function redirectToStripe(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        try {
            // Create the Express connected account on first use and persist its id.
            if (empty($user->stripe_connect_id)) {
                $account = $this->stripe->accounts->create([
                    'type'         => 'express',
                    'email'        => $user->email,
                    'capabilities' => [
                        'card_payments' => ['requested' => true],
                        'transfers'     => ['requested' => true],
                    ],
                    'business_type' => 'individual',
                    'metadata'      => [
                        'user_id' => $user->id,
                    ],
                ]);

                $user->forceFill(['stripe_connect_id' => $account->id])->save();
            }

            // Generate a fresh, single-use onboarding link.
            $accountLink = $this->stripe->accountLinks->create([
                'account'     => $user->stripe_connect_id,
                'refresh_url' => route('stripe.connect.refresh'),
                'return_url'  => route('stripe.connect.completed'),
                'type'        => 'account_onboarding',
            ]);

            return redirect()->away($accountLink->url);
        } catch (ApiErrorException $e) {
            Log::error('Stripe Connect onboarding failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);

            return redirect()
                ->route('dashboard')
                ->with('error', 'We could not start the Stripe onboarding. Please try again.');
        }
    }

    /**
     * Refresh URL — Stripe redirects here when an onboarding link expires or the
     * user needs to restart. Simply kick off a brand new onboarding link.
     */
    public function refresh(Request $request): RedirectResponse
    {
        return $this->redirectToStripe($request);
    }

    /**
     * Return URL — Stripe redirects the user back here after onboarding.
     *
     * Re-fetch the account from Stripe to confirm whether the vendor has fully
     * submitted their details before treating onboarding as complete.
     */
    public function handleCompleted(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (empty($user->stripe_connect_id)) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'No connected Stripe account was found for your profile.');
        }

        try {
            $account = $this->stripe->accounts->retrieve($user->stripe_connect_id);

            // Stripe considers the account ready to receive funds once the
            // onboarding details (identity + bank/IBAN) are submitted and
            // payouts are enabled.
            $payoutsEnabled = (bool) ($account->details_submitted && $account->payouts_enabled);

            $user->forceFill(['stripe_payouts_enabled' => $payoutsEnabled])->save();

            if ($payoutsEnabled) {
                return redirect()
                    ->route('educator.dashboard')
                    ->with('success', 'Your Stripe account is connected and ready to receive payouts.');
            }

            return redirect()
                ->route('educator.settings', ['stripe' => 'required'])
                ->with('error', 'Your Stripe onboarding is incomplete. Please finish adding your IBAN/bank details to receive payouts.');
        } catch (ApiErrorException $e) {
            Log::error('Stripe Connect status check failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);

            return redirect()
                ->route('dashboard')
                ->with('error', 'We could not verify your Stripe account status. Please try again.');
        }
    }

    /**
     * Marketplace checkout — Destination Charge.
     *
     * Creates a Stripe Checkout Session that charges the customer, retains a
     * dynamic application fee for the platform, and transfers the remainder to
     * the seller's connected account.
     */
    public function checkout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'seller_id'    => 'required|exists:users,id',
            'product_name' => 'required|string|max:255',
            'amount'       => 'required|numeric|min:1',
            'currency'     => 'nullable|string|size:3',
        ]);

        $seller = User::find($validated['seller_id']);

        if (empty($seller->stripe_connect_id)) {
            return back()->with('error', 'This seller is not yet able to accept payments.');
        }

        $currency = strtolower($validated['currency'] ?? 'usd');

        // All Stripe amounts are in the smallest currency unit (e.g. cents).
        $amountInCents = (int) round($validated['amount'] * 100);

        // Dynamic platform application fee (default 10%).
        $feePercent       = (float) config('services.stripe.application_fee_percent', 10);
        $applicationFee   = (int) round($amountInCents * ($feePercent / 100));

        try {
            $session = $this->stripe->checkout->sessions->create([
                'mode'                 => 'payment',
                'payment_method_types' => ['card'],
                'line_items'           => [[
                    'price_data' => [
                        'currency'     => $currency,
                        'unit_amount'  => $amountInCents,
                        'product_data' => [
                            'name' => $validated['product_name'],
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'payment_intent_data' => [
                    // Platform keeps this fee; the rest is transferred to the seller.
                    'application_fee_amount' => $applicationFee,
                    'transfer_data' => [
                        'destination' => $seller->stripe_connect_id,
                    ],
                    'metadata' => [
                        'seller_id' => $seller->id,
                        'buyer_id'  => optional(Auth::user())->id,
                    ],
                ],
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('checkout.cancel'),
            ]);

            return redirect()->away($session->url);
        } catch (ApiErrorException $e) {
            Log::error('Stripe marketplace checkout failed', [
                'seller_id' => $seller->id,
                'buyer_id'  => optional(Auth::user())->id,
                'error'     => $e->getMessage(),
            ]);

            return back()->with('error', 'We could not start the checkout. Please try again.');
        }
    }
}
