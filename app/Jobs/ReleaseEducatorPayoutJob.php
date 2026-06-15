<?php

namespace App\Jobs;

use App\Http\Controllers\Educator\PayoutController;
use App\Mail\AdminPayoutBatchProcessedMail;
use App\Mail\EducatorPayoutBatchReleasedMail;
use App\Models\EducatorPayoutRequest;
use App\Models\Payment;
use App\Models\PayoutBatch;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Releases educator payouts on a configurable schedule (defaults to twice a
 * month — see config/payout.php and app/Console/Kernel.php).
 *
 * Can also be dispatched manually when an admin approves an EducatorPayoutRequest
 * (delayed by config payout.approval_delay_minutes, default 2 minutes).
 *
 * Flow:
 *   1. Collect eligible Payment rows (optionally scoped to one educator / ids).
 *   2. Group by educator and sum into a PayoutBatch.
 *   3. Process the transfer (dummy while Stripe sandbox is active).
 *   4. Mark payments processed; resolve linked payout request if any.
 *   5. Email the educator and admin with the outcome.
 */
class ReleaseEducatorPayoutJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $timeout = 120;

    /**
     * @param  int|null  $educatorId  Limit to one educator (required for admin-approved requests).
     * @param  string|null  $processedBy  Label stored on the batch (defaults to config).
     * @param  int|null  $educatorPayoutRequestId  Linked EducatorPayoutRequest to resolve on completion.
     * @param  array<int>|null  $paymentIds  Optional subset of payment ids to include.
     * @param  int|null  $triggeredByUserId  Admin user id when manually triggered.
     */
    public int $educatorId;
    public string $processedBy;
    public int $educatorPayoutRequestId;
    public array $paymentIds;
    public int $triggeredByUserId;

    public function __construct(
        $educatorId,
        $processedBy,
        $educatorPayoutRequestId,
        $paymentIds,
        $triggeredByUserId,
    ) {
        $this->educatorId = $educatorId;
        $this->processedBy = $processedBy;
        $this->educatorPayoutRequestId = $educatorPayoutRequestId;
        $this->paymentIds = $paymentIds;
        $this->triggeredByUserId = $triggeredByUserId;
    }

    public function handle(): void
    {
        $processedBy = $this->processedBy ?? config('payout.processor', 'stripe');

        // STEP 1 — Load eligible payments and group by educator.
        $paymentsByEducator = $this->eligiblePayments()->groupBy('educator_id');

        if ($paymentsByEducator->isEmpty()) {
            $this->logFailure('No payments eligible for payout.', [
                'educator_id'  => $this->educatorId,
                'payment_ids'  => $this->paymentIds,
                'request_id'   => $this->educatorPayoutRequestId,
            ]);

            if ($this->educatorPayoutRequestId) {
                $this->failLinkedRequest('No eligible payments found at processing time.');
            }

            return;
        }

        $this->logSuccess('Payout run started.', [
            'educators'      => $paymentsByEducator->count(),
            'total_payments' => $paymentsByEducator->flatten()->count(),
            'request_id'     => $this->educatorPayoutRequestId,
            'triggered_by'   => $this->triggeredByUserId,
            'processed_by'   => $processedBy,
        ]);

        // STEP 2 — Process one batch per educator in this run.
        foreach ($paymentsByEducator as $educatorId => $payments) {
            $this->releaseForEducator((int) $educatorId, $payments, $processedBy);
        }
    }

    /**
     * Payments still owed to educators: approved status, not yet batched/processed.
     * Scoped by constructor educatorId / paymentIds when provided.
     */
    private function eligiblePayments(): EloquentCollection
    {
        return Payment::query()
            ->whereNotNull('educator_id')
            ->where('is_payout_processed', false)
            ->whereNull('payout_batch_id')
            ->where('status', config('payout.eligible_payment_status', 'approved'))
            ->when($this->educatorId, fn($query) => $query->where('educator_id', $this->educatorId))
            ->when($this->paymentIds, fn($query) => $query->whereIn('id', $this->paymentIds))
            ->orderBy('created_at')
            ->get();
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Payment>  $payments
     */
    private function releaseForEducator(int $educatorId, $payments, string $processedBy): void
    {
        $payments = $payments->sortBy('created_at')->values();
        $educator = User::find($educatorId);

        // STEP 2a — Aggregate payable totals for this educator's batch.
        $paymentIds      = $payments->pluck('id')->all();
        $totalAmount     = round($payments->sum(fn(Payment $p) => (float) $p->gross_amount), 2);
        $totalCommission = round($payments->sum(fn(Payment $p) => (float) $p->gross_amount - PayoutController::payableAmount($p)), 2);
        $totalNetAmount  = round($payments->sum(fn(Payment $p) => PayoutController::payableAmount($p)), 2);
        $currency        = $payments->first()->currency ?: setting('currency', 'USD');
        $startDate       = $payments->first()->created_at;
        $endDate         = $payments->last()->created_at;

        if ($totalNetAmount <= 0 || ! $educator) {
            $this->logFailure('Skipping educator — zero balance or missing user.', [
                'educator_id'     => $educatorId,
                'total_net_amount'=> $totalNetAmount,
                'educator_found'  => (bool) $educator,
            ]);

            return;
        }

        // STEP 2b — Create batch + tag payments atomically (idempotent guard).
        $batch = DB::transaction(function () use (
            $educatorId,
            $paymentIds,
            $payments,
            $totalAmount,
            $totalCommission,
            $totalNetAmount,
            $currency,
            $startDate,
            $endDate,
            $processedBy
        ) {
            $batch = PayoutBatch::create([
                'educator_id'      => $educatorId,
                'payment_ids'      => implode(',', $paymentIds),
                'status'           => 'processing',
                'start_date'       => $startDate,
                'end_date'         => $endDate,
                'total_amount'     => $totalAmount,
                'total_commission' => $totalCommission,
                'total_net_amount' => $totalNetAmount,
                'currency'         => $currency,
                'processed_by'     => $processedBy,
                'description'      => $this->buildBatchDescription($educatorId, $payments->count()),
                'notes'            => $this->educatorPayoutRequestId
                    ? "Triggered by payout request #{$this->educatorPayoutRequestId}"
                    : null,
            ]);

            Payment::whereIn('id', $paymentIds)->update([
                'payout_batch_id' => $batch->id,
                'payout_status'   => 'processing',
            ]);

            return $batch;
        });

        // STEP 3 — Execute transfer inside try/catch; reconcile on success/failure.
        try {
            $result = $this->processPayoutBatch($batch, $educator);

            if (! ($result['success'] ?? false)) {
                throw new \RuntimeException($result['error'] ?? 'Payout processing failed.');
            }

            // STEP 4 — Success: finalize batch + payments, resolve request, notify.
            DB::transaction(function () use ($batch, $paymentIds, $result, $processedBy) {
                $batch->update([
                    'status'          => 'completed',
                    'processed_by'    => $processedBy,
                    'processed_at'    => now(),
                    'stripe_response' => json_encode($result),
                ]);

                Payment::whereIn('id', $paymentIds)->update([
                    'is_payout_processed' => true,
                    'payout_status'       => 'paid',
                ]);

                if ($this->educatorPayoutRequestId) {
                    EducatorPayoutRequest::where('id', $this->educatorPayoutRequestId)->update([
                        'status'          => EducatorPayoutRequest::STATUS_RESOLVED,
                        'payout_batch_id' => $batch->id,
                        'resolved_at'     => now(),
                        'resolved_by'     => $this->triggeredByUserId,
                    ]);
                }
            });

            $this->sendSuccessNotifications($educator, $batch->fresh(), $payments);

            $this->logSuccess('Payout batch completed.', [
                'educator_id'   => $educatorId,
                'educator_email'=> $educator->email,
                'batch_id'      => $batch->id,
                'amount'        => $totalNetAmount,
                'currency'      => $currency,
                'payment_count' => count($paymentIds),
                'payment_ids'   => $paymentIds,
                'processor_ref' => $result['reference'] ?? null,
                'request_id'    => $this->educatorPayoutRequestId,
            ]);
        } catch (\Throwable $e) {
            // STEP 5 — Failure: mark batch failed, detach payments for retry, notify.
            DB::transaction(function () use ($batch, $paymentIds, $e) {
                $batch->update([
                    'status'          => 'failed',
                    'processed_at'    => now(),
                    'stripe_response' => json_encode(['error' => $e->getMessage()]),
                ]);

                Payment::whereIn('id', $paymentIds)->update([
                    'payout_batch_id' => null,
                    'payout_status'   => 'failed',
                ]);
            });

            $this->sendFailureNotifications($educator, $batch->fresh(), $e->getMessage());
            $this->failLinkedRequest($e->getMessage());

            $this->logFailure('Payout batch failed.', [
                'educator_id'   => $educatorId,
                'educator_email'=> $educator->email ?? null,
                'batch_id'      => $batch->id,
                'amount'        => $totalNetAmount,
                'currency'      => $currency,
                'payment_count' => count($paymentIds),
                'payment_ids'   => $paymentIds,
                'error'         => $e->getMessage(),
                'request_id'    => $this->educatorPayoutRequestId,
            ]);
        }
    }

    private function buildBatchDescription(int $educatorId, int $count): string
    {
        $source = $this->educatorPayoutRequestId
            ? "admin-approved request #{$this->educatorPayoutRequestId}"
            : 'scheduled release';

        return "Payout batch for educator #{$educatorId} ({$count} payment(s) via {$source}).";
    }

    /**
     * Dummy Stripe transfer — replace with real API call when going live.
     *
     * @return array{success: bool, reference?: string, amount?: float, currency?: string, mode?: string, error?: string}
     */
    private function processPayoutBatch(PayoutBatch $batch, User $educator): array
    {
        if (! $educator->canReceivePayouts()) {
            return [
                'success' => false,
                'error'   => 'Educator has not completed Stripe Connect onboarding.',
            ];
        }

        return [
            'success'      => true,
            'reference'    => 'dummy_' . strtoupper(Str::random(24)),
            'amount'       => (float) $batch->total_net_amount,
            'currency'     => $batch->currency,
            'mode'         => 'sandbox',
            'processed_at' => now()->toIso8601String(),
        ];
    }

    /** STEP 4a — Email educator (success) and admin inbox. */
    private function sendSuccessNotifications(User $educator, PayoutBatch $batch, $payments): void
    {
        try {
            if ($educator->email) {
                EmailService::send(
                    $educator->email,
                    new EducatorPayoutBatchReleasedMail($educator, $batch, $payments),
                    'emails'
                );
            }
        } catch (\Throwable $e) {
            $this->logFailure('Educator success email failed.', [
                'educator_id' => $educator->id,
                'batch_id'    => $batch->id,
                'error'       => $e->getMessage(),
            ]);
        }

        $this->notifyAdmin($batch, $educator, true);
    }

    /** STEP 5a — Email educator (failure is admin-only) and admin inbox. */
    private function sendFailureNotifications(User $educator, PayoutBatch $batch, string $error): void
    {
        $this->notifyAdmin($batch, $educator, false, $error);
    }

    private function notifyAdmin(PayoutBatch $batch, User $educator, bool $success, ?string $error = null): void
    {
        $adminEmail = config('payout.admin_notification_email');

        if (! $adminEmail) {
            return;
        }

        try {
            EmailService::send(
                $adminEmail,
                new AdminPayoutBatchProcessedMail($batch, $educator, $success, $error),
                'emails'
            );
        } catch (\Throwable $e) {
            $this->logFailure('Admin notification email failed.', [
                'batch_id'    => $batch->id,
                'educator_id' => $educator->id,
                'success'     => $success,
                'error'       => $e->getMessage(),
            ]);
        }
    }

    /** Write to storage/logs/release_payout_success-*.log */
    private function logSuccess(string $message, array $context = []): void
    {
        Log::channel('release_payout_success')->info($message, $context);
    }

    /** Write to storage/logs/release_payout_failure-*.log */
    private function logFailure(string $message, array $context = []): void
    {
        Log::channel('release_payout_failure')->error($message, $context);
    }

    /** Mark a linked payout request as closed when processing cannot proceed. */
    private function failLinkedRequest(string $reason): void
    {
        if (! $this->educatorPayoutRequestId) {
            return;
        }

        $request = EducatorPayoutRequest::find($this->educatorPayoutRequestId);

        if (! $request) {
            return;
        }

        $note = trim(($request->admin_notes ?? '') . "\n[Auto] Processing failed: {$reason}");

        $request->update([
            'status'      => EducatorPayoutRequest::STATUS_CLOSED,
            'admin_notes' => $note,
            'resolved_at' => now(),
            'resolved_by' => $this->triggeredByUserId,
        ]);

        $this->logFailure('Linked payout request closed after processing failure.', [
            'request_id'  => $request->id,
            'educator_id' => $request->educator_id,
            'reason'      => $reason,
        ]);
    }
}
