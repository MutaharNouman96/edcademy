<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Earning;
use App\Models\Payout;
use Carbon\Carbon;

class FinancialReportsController extends Controller
{
    public function index(Request $request)
    {
        // Get date range
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Earnings summary
        $earningsQuery = Earning::whereBetween('earned_at', [$startDate, $endDate]);

        $totalEarnings = $earningsQuery->sum('net_amount');
        $totalGross = $earningsQuery->sum('gross_amount');
        $totalPlatformCommission = $earningsQuery->sum('platform_commission');

        // Payouts summary
        $payoutsQuery = Payout::whereBetween('created_at', [$startDate, $endDate]);

        $totalPayouts = $payoutsQuery->sum('amount');
        $pendingPayouts = $payoutsQuery->where('status', 'pending')->sum('amount');
        $processedPayouts = $payoutsQuery->where('status', 'processed')->sum('amount');

        // Overall financial summary
        $netRevenue = $totalPlatformCommission; // Platform keeps the commission
        $totalPaidToEducators = $totalPayouts;

        return view('admin.financial-reports.index', compact(
            'totalEarnings',
            'totalGross',
            'totalPlatformCommission',
            'totalPayouts',
            'pendingPayouts',
            'processedPayouts',
            'netRevenue',
            'totalPaidToEducators',
            'startDate',
            'endDate'
        ));
    }
}