<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Educator\PayoutController as EducatorPayoutHelper;
use App\Jobs\ReleaseEducatorPayoutJob;
use App\Models\EducatorPayoutRequest;
use App\Models\Payment;
use App\Models\PayoutBatch;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Admin payout hub — payments (earnings), released batches, and payout requests.
 * Earnings are derived from the Payment model (legacy Earning model removed from UI).
 */
class PayoutController extends Controller
{
    /**
     * Main payouts dashboard with tabbed views.
     */
    public function index(Request $request): View
    {
        $view = $request->get('view', 'payments');

        $educators = User::where('role', 'educator')->select('id', 'first_name', 'last_name')->orderBy('first_name')->get();
        $summary   = $this->paymentSummary();
        $schedule  = $this->scheduleInfo();

        return match ($view) {
            'batches'  => $this->paymentsView($request, $educators, $summary, $schedule, 'batches'),
            'requests' => $this->paymentsView($request, $educators, $summary, $schedule, 'requests'),
            default    => $this->paymentsView($request, $educators, $summary, $schedule, 'payments'),
        };
    }

    /**
     * Build shared view data for all tabs.
     */
    private function paymentsView(Request $request, $educators, array $summary, array $schedule, string $currentView): View
    {
        $data = compact('educators', 'summary', 'schedule', 'currentView');

        if ($currentView === 'payments') {
            $data['payments'] = $this->filteredPayments($request)->paginate(15)->withQueryString();
        }

        if ($currentView === 'batches') {
            $data['batches'] = $this->filteredBatches($request)->paginate(15)->withQueryString();
        }

        if ($currentView === 'requests') {
            $data['payoutRequests'] = $this->filteredPayoutRequests($request)->paginate(15)->withQueryString();
            $data['pendingRequestCount'] = EducatorPayoutRequest::where('status', EducatorPayoutRequest::STATUS_PENDING)->count();
            $data['inProgressRequestCount'] = EducatorPayoutRequest::where('status', EducatorPayoutRequest::STATUS_IN_PROGRESS)->count();
        }

        return view('admin.managePayouts', $data);
    }

    /**
     * Platform-wide payment totals (replaces legacy Earning aggregates).
     */
    private function paymentSummary(): array
    {
        $approved = Payment::where('status', config('payout.eligible_payment_status', 'approved'))->get();

        $net = fn (Payment $p) => EducatorPayoutHelper::payableAmount($p);

        return [
            'total_gross'      => round($approved->sum('gross_amount'), 2),
            'total_net'        => round($approved->sum($net), 2),
            'pending_payout'   => round($approved->where('is_payout_processed', false)->whereNull('payout_batch_id')->sum($net), 2),
            'processing'       => round($approved->where('is_payout_processed', false)->whereNotNull('payout_batch_id')->sum($net), 2),
            'paid_out'         => round($approved->where('is_payout_processed', true)->sum($net), 2),
            'payment_count'    => $approved->count(),
            'pending_count'    => $approved->where('is_payout_processed', false)->whereNull('payout_batch_id')->count(),
            'batch_completed'  => PayoutBatch::where('status', 'completed')->count(),
            'batch_failed'     => PayoutBatch::where('status', 'failed')->count(),
        ];
    }

    private function scheduleInfo(): array
    {
        return [
            'label'                  => $this->scheduleLabel(),
            'approval_delay_minutes' => config('payout.approval_delay_minutes', 2),
            'processor'              => config('payout.processor', 'stripe'),
        ];
    }

    private function scheduleLabel(): string
    {
        return match (config('payout.schedule', 'twice_monthly')) {
            'every_two_minutes' => 'Every 2 minutes (testing)',
            'hourly'            => 'Hourly',
            'daily'             => 'Daily at ' . config('payout.run_at', '00:00'),
            'weekly'            => 'Weekly (Mondays)',
            'monthly'           => 'Monthly on day ' . config('payout.monthly_day', 1),
            default             => 'Twice a month (days ' . implode(' & ', config('payout.twice_monthly_days', [1, 16])) . ')',
        };
    }

    private function filteredPayments(Request $request)
    {
        $query = Payment::with(['educator', 'student', 'course', 'payoutBatch'])
            ->whereNotNull('educator_id');

        if ($request->filled('educator_id')) {
            $query->where('educator_id', $request->educator_id);
        }

        if ($request->filled('payout_status')) {
            match ($request->payout_status) {
                'pending'    => $query->where('is_payout_processed', false)->whereNull('payout_batch_id'),
                'processing' => $query->where('is_payout_processed', false)->whereNotNull('payout_batch_id'),
                'paid'       => $query->where('is_payout_processed', true),
                'failed'     => $query->where('payout_status', 'failed'),
                default      => null,
            };
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('educator', fn ($eq) => $eq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"))
                    ->orWhereHas('course', fn ($cq) => $cq->where('title', 'like', "%{$search}%"));
            });
        }

        return $query->latest();
    }

    private function filteredBatches(Request $request)
    {
        $query = PayoutBatch::with('educator');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('educator_id')) {
            $query->where('educator_id', $request->educator_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query->latest();
    }

    private function filteredPayoutRequests(Request $request)
    {
        $query = EducatorPayoutRequest::with(['educator', 'payment.course', 'payoutBatch', 'resolver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('educator_id')) {
            $query->where('educator_id', $request->educator_id);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->whereHas('educator', fn ($eq) => $eq->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        return $query->latest();
    }

    /**
     * Detail page for a single released/processing payout batch.
     */
    public function showBatch(PayoutBatch $batch): View
    {
        $batch->load('educator');

        $paymentIds = array_filter(explode(',', $batch->payment_ids ?? ''));
        $payments   = Payment::with(['student', 'course'])
            ->whereIn('id', $paymentIds)
            ->get();

        $stripeResponse = json_decode($batch->stripe_response ?? '{}', true);

        return view('admin.payout-batches.show', compact('batch', 'payments', 'stripeResponse'));
    }

    /**
     * Manually trigger the scheduled release job for all educators now.
     */
    public function runScheduledRelease(Request $request): RedirectResponse
    {
        ReleaseEducatorPayoutJob::dispatch(
            processedBy: 'admin_manual_' . $request->user()->id,
            triggeredByUserId: $request->user()->id,
        );

        return back()->with('success', 'Scheduled payout release job has been queued.');
    }

    /**
     * Queue an immediate release for a single educator (admin override).
     */
    public function runEducatorRelease(Request $request, User $educator): RedirectResponse
    {
        abort_unless($educator->role === 'educator', 404);

        ReleaseEducatorPayoutJob::dispatch(
            educatorId: $educator->id,
            processedBy: 'admin_manual_' . $request->user()->id,
            triggeredByUserId: $request->user()->id,
        );

        return back()->with('success', "Payout release queued for {$educator->full_name}.");
    }
}
