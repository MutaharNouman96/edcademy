<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EducatorPayout;
use App\Models\EducatorBank;
use App\Models\EducatorPayment;
use App\Models\Educator;
use Illuminate\Support\Facades\Auth;

class PayoutController extends Controller
{
    /**
     * Main payout dashboard
     */
    public function index()
    {
        return view('crm.educator.payout.index');
    }

    /**
     * KPIs
     */
    public function kpis()
    {
        $educatorId = Auth::id();

        $escrow = EducatorPayment::where('educator_id', $educatorId)
            ->where('status', 'processing')
            ->sum('net_amount');

        $available = EducatorPayment::where('educator_id', $educatorId)
            ->where('status', 'available')
            ->sum('net_amount');

        $paidThisMonth = EducatorPayout::where('educator_id', $educatorId)
            ->whereMonth('processed_at', now()->month)
            ->whereYear('processed_at', now()->year)
            ->sum('amount');

        $paidCount = EducatorPayout::where('educator_id', $educatorId)
            ->whereMonth('processed_at', now()->month)
            ->count();

        $lifetime = EducatorPayout::where('educator_id', $educatorId)
            ->sum('amount');

        return response()->json([
            'escrow'        => number_format($escrow, 2),
            'available'     => number_format($available, 2),
            'paid_month'    => number_format($paidThisMonth, 2),
            'paid_count'    => $paidCount,
            'lifetime'      => number_format($lifetime, 2),
        ]);
    }

    /**
     * Upcoming releases (payments still processing)
     */
    public function upcoming(Request $request)
    {
        $educatorId = Auth::id();

        $query = EducatorPayment::where('educator_id', $educatorId)
            ->whereIn('payout_status', ['processing', 'pending']);

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        return response()->json(
            $query->latest()->get()->map(function ($p) {
                return [
                    'release_date' => $p->created_at->addDays(14)->format('d M Y'),
                    'source'       => 'Order #' . $p->order_id,
                    'amount'       => number_format($p->net_amount, 2),
                    'status'       => ucfirst($p->status),
                ];
            })
        );
    }

    /**
     * Payout history
     */
    public function history(Request $request)
    {
        $educatorId = Auth::id();

        $query = EducatorPayout::with('payment')
            ->where('educator_id', $educatorId);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        return response()->json(
            $query->latest()->get()->map(function ($p) {
                return [
                    'date'     => optional($p->processed_at)->format('d M Y'),
                    'ref'      => 'PAY-' . $p->id,
                    'method'   => 'Bank Transfer',
                    'amount'   => number_format($p->amount, 2),
                    'fees'     => '—',
                    'net'      => number_format($p->amount, 2),
                    'status'   => ucfirst($p->status),
                    'note'     => $p->description,
                ];
            })
        );
    }

    /**
     * Bank accounts list
     */
    public function banks()
    {
        return response()->json(
            EducatorBank::where('educator_id', Auth::id())->get()
        );
    }

    /**
     * Store / Update bank (used by modal)
     */
    public function saveBank(Request $request)
    {
        $request->validate([
            'bank_name'     => 'required|string',
            'account_name'  => 'required|string',
            'iban'          => 'required|string',
        ]);

        EducatorBank::updateOrCreate(
            [
                'id' => $request->id,
                'educator_id' => Auth::id(),
            ],
            [
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'iban' => $request->iban,
                'approval_status' => '0',
            ]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Delete bank
     */
    public function deleteBank($id)
    {
        EducatorBank::where('id', $id)
            ->where('educator_id', Auth::id())
            ->delete();

        return response()->json(['success' => true]);
    }
}
