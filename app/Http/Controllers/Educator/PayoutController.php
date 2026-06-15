<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\EducatorPayoutRequest;
use App\Models\Payment;
use App\Models\PayoutBatch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PayoutController extends Controller
{
    /**
     * Payout dashboard — earnings summary, pending payments, batch history,
     * and the on-page payout request form.
     */
    public function index(): View
    {
        $educatorId = Auth::id();
        $currency   = setting('currency', 'USD');

        $paymentsQuery = Payment::query()->where('educator_id', $educatorId);

        $pendingPayments = (clone $paymentsQuery)
            ->where('is_payout_processed', false)
            ->whereNull('payout_batch_id')
            ->where('status', config('payout.eligible_payment_status', 'approved'))
            ->with(['student', 'course'])
            ->latest()
            ->get();

        $processingPayments = (clone $paymentsQuery)
            ->where('is_payout_processed', false)
            ->whereNotNull('payout_batch_id')
            ->with(['student', 'course', 'payoutBatch'])
            ->latest()
            ->get();

        $pendingBalance = $this->sumPayable($pendingPayments);
        $processingBalance = $this->sumPayable($processingPayments);

        $paidThisMonth = PayoutBatch::where('educator_id', $educatorId)
            ->where('status', 'completed')
            ->whereMonth('processed_at', now()->month)
            ->whereYear('processed_at', now()->year)
            ->sum('total_net_amount');

        $paidThisMonthCount = PayoutBatch::where('educator_id', $educatorId)
            ->where('status', 'completed')
            ->whereMonth('processed_at', now()->month)
            ->whereYear('processed_at', now()->year)
            ->count();

        $lifetimePaid = PayoutBatch::where('educator_id', $educatorId)
            ->where('status', 'completed')
            ->sum('total_net_amount');

        $totalEarned = (clone $paymentsQuery)
            ->where('status', config('payout.eligible_payment_status', 'approved'))
            ->get()
            ->sum(fn (Payment $p) => $this->payableAmount($p));

        $payoutBatches = PayoutBatch::where('educator_id', $educatorId)
            ->latest()
            ->paginate(10, ['*'], 'batches');

        $recentRequests = EducatorPayoutRequest::where('educator_id', $educatorId)
            ->latest()
            ->limit(5)
            ->get();

        $openPayoutRequest = EducatorPayoutRequest::where('educator_id', $educatorId)
            ->open()
            ->latest()
            ->first();

        return view('crm.educator.payout.index', [
            'currency'            => $currency,
            'pendingBalance'      => $pendingBalance,
            'processingBalance'   => $processingBalance,
            'paidThisMonth'       => $paidThisMonth,
            'paidThisMonthCount'  => $paidThisMonthCount,
            'lifetimePaid'        => $lifetimePaid,
            'totalEarned'         => $totalEarned,
            'pendingPayments'     => $pendingPayments,
            'processingPayments'  => $processingPayments,
            'payoutBatches'       => $payoutBatches,
            'recentRequests'      => $recentRequests,
            'openPayoutRequest'   => $openPayoutRequest,
            'nextPayoutDate'      => $this->nextScheduledPayoutDate(),
            'scheduleLabel'       => $this->scheduleLabel(),
            'canReceivePayouts'   => Auth::user()->canReceivePayouts(),
        ]);
    }

    /**
     * Net amount payable for a single payment row.
     */
    public static function payableAmount(Payment $payment): float
    {
        if ($payment->net_amount !== null && (float) $payment->net_amount > 0) {
            return (float) $payment->net_amount;
        }

        $commission = (float) ($payment->platform_commission ?? 0);

        if ($commission <= 0 && $payment->educator_id) {
            $educator = User::find($payment->educator_id);
            $rate     = $educator?->commissionRate() ?? User::DEFAULT_COMMISSION_RATE;
            $commission = round((float) $payment->gross_amount * $rate / 100, 2);
        }

        return round((float) $payment->gross_amount - $commission, 2);
    }

    private function sumPayable($payments): float
    {
        return round($payments->sum(fn (Payment $p) => $this->payableAmount($p)), 2);
    }

    private function scheduleLabel(): string
    {
        return match (config('payout.schedule', 'twice_monthly')) {
            'every_two_minutes' => 'Every 2 minutes (testing)',
            'hourly'            => 'Hourly',
            'daily'             => 'Daily at ' . config('payout.run_at', '00:00'),
            'weekly'            => 'Weekly (Mondays at ' . config('payout.run_at', '00:00') . ')',
            'monthly'           => 'Monthly on day ' . config('payout.monthly_day', 1),
            default             => 'Twice a month (days '
                . implode(' & ', config('payout.twice_monthly_days', [1, 16])) . ')',
        };
    }

    private function nextScheduledPayoutDate(): ?Carbon
    {
        $schedule = config('payout.schedule', 'twice_monthly');
        $runAt    = config('payout.run_at', '00:00');
        $now      = now();

        $candidates = match ($schedule) {
            'every_two_minutes' => [$now->copy()->addMinutes(2)],
            'hourly'            => [$now->copy()->addHour()->startOfHour()],
            'daily'             => [$now->copy()->addDay()->setTimeFromTimeString($runAt)],
            'weekly'            => [$now->copy()->next(Carbon::MONDAY)->setTimeFromTimeString($runAt)],
            'monthly'           => $this->monthlyCandidates($now, [config('payout.monthly_day', 1)], $runAt),
            default             => $this->monthlyCandidates($now, config('payout.twice_monthly_days', [1, 16]), $runAt),
        };

        foreach ($candidates as $date) {
            if ($date->isFuture()) {
                return $date;
            }
        }

        return $candidates[0] ?? null;
    }

    /**
     * @param  array<int>  $days
     * @return array<int, Carbon>
     */
    private function monthlyCandidates(Carbon $from, array $days, string $runAt): array
    {
        $candidates = [];

        foreach ([0, 1] as $monthOffset) {
            $month = $from->copy()->addMonths($monthOffset);

            foreach ($days as $day) {
                $safeDay = min((int) $day, $month->daysInMonth);
                $candidates[] = $month->copy()->day($safeDay)->setTimeFromTimeString($runAt);
            }
        }

        usort($candidates, fn (Carbon $a, Carbon $b) => $a <=> $b);

        return $candidates;
    }
}
