<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Educator\PayoutController;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EducatorPaymentController extends Controller
{
    public function index(): View
    {
        $educatorId = Auth::id();

        $allPayments = Payment::where('educator_id', $educatorId)
            ->where('status', config('payout.eligible_payment_status', 'approved'))
            ->get();

        $summary = [
            'total_received'   => $allPayments->sum('gross_amount'),
            'total_tax'        => $allPayments->sum('tax_amount'),
            'total_commission' => $allPayments->sum(fn (Payment $p) => $this->commissionFor($p)),
            'total_net'        => $allPayments->sum(fn (Payment $p) => PayoutController::payableAmount($p)),
            'pending_payout'   => $allPayments->where('is_payout_processed', false)->sum(fn (Payment $p) => PayoutController::payableAmount($p)),
            'paid_out'         => $allPayments->where('is_payout_processed', true)->sum(fn (Payment $p) => PayoutController::payableAmount($p)),
        ];

        $payments = Payment::with([ 'course', 'payoutBatch'])
            ->where('educator_id', $educatorId)
            ->latest()
            ->paginate(20);

        return view('crm.educator.payments.index', compact('payments', 'summary'));
    }

    public function show(Payment $payment): View
    {
        abort_if($payment->educator_id !== Auth::id(), 403);

        $payment->load(['student', 'course', 'payoutBatch', 'educator']);

        return view('crm.educator.payments.show', compact('payment'));
    }

    private function commissionFor(Payment $payment): float
    {
        if ((float) $payment->platform_commission > 0) {
            return (float) $payment->platform_commission;
        }

        return round((float) $payment->gross_amount - PayoutController::payableAmount($payment), 2);
    }
}
