<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Earning;
use Illuminate\Support\Facades\Auth;

class EarningController extends Controller
{
    //

    public function index()
    {
        $earnings = Earning::with(['payment', 'payout', 'course', 'session'])
            ->where('educator_id', Auth::id())
            ->latest()
            ->paginate(10);

        // Totals for summary cards
        $totalApproved = Earning::where('educator_id', Auth::id())->where('status', 'approved')->sum('net_amount');
        $totalPaid = Earning::where('educator_id', Auth::id())->where('status', 'paid')->sum('net_amount');
        $totalPending = Earning::where('educator_id', Auth::id())->where('status', 'pending')->sum('net_amount');

        return view('crm.educator.earnings.index', compact('earnings', 'totalApproved', 'totalPaid', 'totalPending'));
    }

    // Show individual earning detail
    public function show(Earning $earning)
    {
        if ($earning->educator_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $earning->load(['payment', 'payout', 'course', 'session', 'courseResource']);
        return view('crm.educator.earnings.show', compact('earning'));
    }
}
