<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\EducatorPayoutRequest;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayoutRequestController extends Controller
{
    /**
     * List all payout requests submitted by the logged-in educator.
     */
    public function index(): View
    {
        $requests = EducatorPayoutRequest::where('educator_id', auth()->id())
            ->with(['payment.course', 'payoutBatch', 'resolver'])
            ->latest()
            ->paginate(15);

        $openCount = EducatorPayoutRequest::where('educator_id', auth()->id())
            ->open()
            ->count();

        return view('crm.educator.payout-requests.index', compact('requests', 'openCount'));
    }

    /**
     * Submit a payout release request (from the Payouts page form).
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->input('type') === 'assist') {
            return $this->storeAssist($request);
        }

        return $this->storePayoutRequest($request);
    }

    /**
     * Ask admin for help setting up Stripe / bank details.
     */
    private function storeAssist(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->canReceivePayouts()) {
            return back()->with('success', 'Your payout setup is already complete.');
        }

        if ($this->hasOpenRequest($user->id)) {
            return back()->with('message', 'You already have an open assistance request. Our team will contact you soon.');
        }

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        EducatorPayoutRequest::create([
            'educator_id' => $user->id,
            'message'     => $validated['message'] ?? 'Request for payout setup assistance.',
            'status'      => EducatorPayoutRequest::STATUS_PENDING,
        ]);

        return back()->with('success', 'Your payout assistance request has been sent to the admin team.');
    }

    /**
     * Request an early or manual payout of pending earnings.
     */
    private function storePayoutRequest(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->canReceivePayouts()) {
            return back()->with('error', 'Complete Stripe Connect setup before requesting a payout.');
        }

        $validated = $request->validate([
            'message'    => ['nullable', 'string', 'max:2000'],
            'payment_id' => ['nullable', 'integer', 'exists:payments,id'],
        ]);

        $pendingQuery = Payment::where('educator_id', $user->id)
            ->where('is_payout_processed', false)
            ->whereNull('payout_batch_id')
            ->where('status', config('payout.eligible_payment_status', 'approved'));

        if ((clone $pendingQuery)->doesntExist()) {
            return back()->with('error', 'You have no pending earnings available to request a payout for.');
        }

        if ($this->hasOpenRequest($user->id)) {
            return back()->with('message', 'You already have an open payout request. Please wait for admin review.');
        }

        $payment = null;

        if (! empty($validated['payment_id'])) {
            $payment = (clone $pendingQuery)->where('id', $validated['payment_id'])->first();

            if (! $payment) {
                return back()->with('error', 'The selected payment is not eligible for payout.');
            }
        }

        $payoutRequest = EducatorPayoutRequest::create([
            'educator_id' => $user->id,
            'payment_id'  => $payment?->id,
            'message'     => $validated['message']
                ?? ($payment
                    ? "Payout request for payment #{$payment->id}."
                    : 'Payout request for all pending earnings.'),
            'status'      => EducatorPayoutRequest::STATUS_PENDING,
        ]);

        if ($payment) {
            $payment->update([
                'is_payout_requested'   => true,
                'payout_requested_at'   => now(),
            ]);
        } else {
            (clone $pendingQuery)->update([
                'is_payout_requested' => true,
                'payout_requested_at' => now(),
            ]);
        }

        return redirect()
            ->route('educator.payout-requests.index')
            ->with('success', "Payout request #{$payoutRequest->id} submitted. Our team will review it shortly.");
    }

    private function hasOpenRequest(int $educatorId): bool
    {
        return EducatorPayoutRequest::where('educator_id', $educatorId)->open()->exists();
    }
}
