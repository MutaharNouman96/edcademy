<?php

namespace App\Http\Controllers;

use App\Models\EducatorPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducatorPaymentController extends Controller
{
    public function index()
    {
        $payments = EducatorPayment::with([
            'order',
            'orderItem.item' // 👈 morph loading
        ])
            ->where('educator_id', Auth::id());
            

        $summary = [
            'total_received' => $payments->sum('gross_amount'),
            'total_tax' => $payments->sum('tax_amount'),
            'total_commission' => $payments->sum('platform_commission'),
            'total_net' => $payments->sum('net_amount'),
        ];

        $payments = $payments->latest()->paginate(20);

        return view('crm.educator.payments.index', compact('payments' , 'summary'));
    }

    /**
     * Show single payment
     */
    public function show(EducatorPayment $payment)
    {
        // Security check
        abort_if($payment->educator_id !== Auth::id(), 403);

        $payment->load([
            'order',
            'orderItem.item'
        ]);

        return view('crm.educator.payments.show', compact('payment'));
    }
}
