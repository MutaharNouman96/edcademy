<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ReleaseEducatorPayoutJob;
use App\Mail\EducatorPayoutRequestApprovedMail;
use App\Models\EducatorPayoutRequest;
use App\Models\Payment;
use App\Services\EmailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class EducatorPayoutRequestController extends Controller
{
    public function index(Request $request): View
    {
        return redirect()->route('admin.payouts.index', array_merge(
            $request->only(['status', 'q']),
            ['view' => 'requests']
        ));
    }

    public function show(EducatorPayoutRequest $educatorPayoutRequest): View
    {
        $educatorPayoutRequest->load([
            'educator.educatorProfile',
            'resolver',
            'payment.course',
            'payoutBatch',
        ]);

        $pendingPayments = Payment::where('educator_id', $educatorPayoutRequest->educator_id)
            ->where('is_payout_processed', false)
            ->whereNull('payout_batch_id')
            ->where('status', config('payout.eligible_payment_status', 'approved'))
            ->with('course')
            ->latest()
            ->get();

        return view('admin.educator-payout-requests.show', [
            'payoutRequest'     => $educatorPayoutRequest,
            'pendingPayments'   => $pendingPayments,
            'approvalDelay'     => config('payout.approval_delay_minutes', 2),
        ]);
    }

    public function update(Request $request, EducatorPayoutRequest $educatorPayoutRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status'      => ['required', 'in:pending,in_progress,resolved,closed'],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $data = [
            'status'      => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
        ];

        if (in_array($validated['status'], [EducatorPayoutRequest::STATUS_RESOLVED, EducatorPayoutRequest::STATUS_CLOSED], true)) {
            $data['resolved_by'] = $request->user()->id;
            $data['resolved_at'] = now();
        } else {
            $data['resolved_by'] = null;
            $data['resolved_at'] = null;
        }

        $educatorPayoutRequest->update($data);

        return redirect()
            ->route('admin.educator-payout-requests.show', $educatorPayoutRequest)
            ->with('success', 'Payout request updated.');
    }

    /**
     * Approve a payout request and queue ReleaseEducatorPayoutJob after the
     * configured delay (default 2 minutes — see config/payout.php).
     */
    public function approve(Request $request, EducatorPayoutRequest $educatorPayoutRequest): RedirectResponse
    {
        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        // STEP 1 — Guard: only open requests can be approved.
        if (! $educatorPayoutRequest->isOpen()) {
            return back()->with('error', 'This request is no longer open for approval.');
        }

        $educator = $educatorPayoutRequest->educator;

        if (! $educator) {
            return back()->with('error', 'Educator account not found.');
        }

        // STEP 2 — For payout release requests the educator must have Stripe ready.
        $isPayoutRelease = $educatorPayoutRequest->payment_id !== null
            || Payment::where('educator_id', $educator->id)
                ->where('is_payout_processed', false)
                ->whereNull('payout_batch_id')
                ->where('status', config('payout.eligible_payment_status', 'approved'))
                ->exists();

        if ($isPayoutRelease && ! $educator->canReceivePayouts()) {
            return back()->with('error', 'Educator must complete Stripe Connect before a payout can be released.');
        }

        // STEP 3 — Confirm there are eligible payments for the requested scope.
        $paymentIds = null;

        if ($educatorPayoutRequest->payment_id) {
            $eligible = Payment::where('id', $educatorPayoutRequest->payment_id)
                ->where('educator_id', $educator->id)
                ->where('is_payout_processed', false)
                ->whereNull('payout_batch_id')
                ->where('status', config('payout.eligible_payment_status', 'approved'))
                ->exists();

            if (! $eligible) {
                return back()->with('error', 'The linked payment is no longer eligible for payout.');
            }

            $paymentIds = [$educatorPayoutRequest->payment_id];
        } elseif ($isPayoutRelease) {
            $pendingIds = Payment::where('educator_id', $educator->id)
                ->where('is_payout_processed', false)
                ->whereNull('payout_batch_id')
                ->where('status', config('payout.eligible_payment_status', 'approved'))
                ->pluck('id')
                ->all();

            if (empty($pendingIds)) {
                return back()->with('error', 'No pending payments available for this educator.');
            }

            $paymentIds = $pendingIds;
        }

        $delayMinutes = (int) config('payout.approval_delay_minutes', 2);

        // STEP 4 — Mark request in progress before queuing the job.
        $educatorPayoutRequest->update([
            'status'      => EducatorPayoutRequest::STATUS_IN_PROGRESS,
            'admin_notes' => $validated['admin_notes'] ?? $educatorPayoutRequest->admin_notes,
        ]);

        // STEP 5 — Queue the release job with configured delay.
        if ($isPayoutRelease && $paymentIds !== null) {
            ReleaseEducatorPayoutJob::dispatch(
                educatorId: $educator->id,
                processedBy: 'admin_approved_' . $request->user()->id,
                educatorPayoutRequestId: $educatorPayoutRequest->id,
                paymentIds: $paymentIds,
                triggeredByUserId: $request->user()->id,
            )->delay(now()->addMinutes($delayMinutes));
        }

        // STEP 6 — Notify educator that approval was received (payout release only).
        if ($isPayoutRelease && $educator->email) {
            try {
                EmailService::send(
                    $educator->email,
                    new EducatorPayoutRequestApprovedMail($educator, $educatorPayoutRequest->fresh(), $delayMinutes),
                    'emails'
                );
            } catch (\Throwable $e) {
                Log::error('Failed to send payout request approved email.', ['error' => $e->getMessage()]);
            }
        }

        $message = $isPayoutRelease
            ? "Payout approved. Release job queued — will run in {$delayMinutes} minute(s)."
            : 'Assistance request marked in progress.';

        return redirect()
            ->route('admin.educator-payout-requests.show', $educatorPayoutRequest)
            ->with('success', $message);
    }
}
