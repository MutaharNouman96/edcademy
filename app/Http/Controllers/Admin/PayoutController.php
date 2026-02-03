<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Earning;
use App\Models\Payout;
use App\Models\EducatorPayout;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\EmailService;
use App\Services\ActivityNotificationService;
use App\Mail\EducatorPayoutMail;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        // Handle different views: earnings, payouts, educator_payouts
        $view = $request->get('view', 'earnings');

        switch ($view) {
            case 'payouts':
                return $this->managePayouts($request);
            case 'educator_payouts':
                return $this->manageEducatorPayouts($request);
            case 'earnings':
            default:
                return $this->manageEarnings($request);
        }
    }

    private function manageEarnings(Request $request)
    {
        $query = Earning::with(['educator', 'course', 'session']);

        // Filter by status
        if ($request->filled('status')) {
            if (in_array($request->status, ['pending', 'approved', 'paid', 'cancelled'])) {
                $query->where('status', $request->status);
            }
        }

        // Filter by source type
        if ($request->filled('source_type')) {
            $query->where('source_type', $request->source_type);
        }

        // Filter by educator
        if ($request->filled('educator_id')) {
            $query->where('educator_id', $request->educator_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('earned_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('earned_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Search by description or educator name
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', '%' . $search . '%')
                  ->orWhereHas('educator', function ($educatorQuery) use ($search) {
                      $educatorQuery->where('first_name', 'like', '%' . $search . '%')
                                    ->orWhere('last_name', 'like', '%' . $search . '%')
                                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%']);
                  });
            });
        }

        $earnings = $query->latest('earned_at')->paginate(15);

        // Get summary statistics
        $totalEarnings = Earning::sum('net_amount');
        $pendingEarnings = Earning::where('status', 'pending')->sum('net_amount');
        $approvedEarnings = Earning::where('status', 'approved')->sum('net_amount');
        $paidEarnings = Earning::where('status', 'paid')->sum('net_amount');

        // Get educators for filter dropdown
        $educators = User::where('role', 'educator')->select('id', 'first_name', 'last_name')->get();

        return view('admin.managePayouts', compact(
            'earnings',
            'totalEarnings',
            'pendingEarnings',
            'approvedEarnings',
            'paidEarnings',
            'educators'
        ), ['currentView' => 'earnings']);
    }

    private function managePayouts(Request $request)
    {
        $query = Payout::with('educator');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by educator
        if ($request->filled('educator_id')) {
            $query->where('educator_id', $request->educator_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $payouts = $query->latest()->paginate(15);

        // Get educators for filter dropdown
        $educators = User::where('role', 'educator')->select('id', 'first_name', 'last_name')->get();

        return view('admin.managePayouts', compact('payouts', 'educators'), ['currentView' => 'payouts']);
    }

    private function manageEducatorPayouts(Request $request)
    {
        $query = EducatorPayout::with('educator');

        // Filter by status
        if ($request->filled('status')) {
            if (in_array($request->status, ['pending', 'completed', 'failed'])) {
                $query->where('status', $request->status);
            }
        }

        // Filter by educator
        if ($request->filled('educator_id')) {
            $query->where('educator_id', $request->educator_id);
        }

        // Filter by payment_id
        if ($request->filled('payment_id')) {
            $query->where('payment_id', 'like', '%' . $request->payment_id . '%');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $educatorPayouts = $query->latest()->paginate(15);

        // Get educators for filter dropdown
        $educators = User::where('role', 'educator')->select('id', 'first_name', 'last_name')->get();

        return view('admin.managePayouts', compact('educatorPayouts', 'educators'), ['currentView' => 'educator_payouts']);
    }

    public function show(Payout $payout)
    {
        $payout->load(['educator', 'earning']);
        return view('admin.payoutDetail', compact('payout'));
    }

    public function process(Request $request, Payout $payout)
    {
        $request->validate([
            'status' => 'required|in:completed,failed',
            'processed_by' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $oldStatus = $payout->status;
        $payout->update([
            'status' => $request->status,
            'processed_at' => now(),
            'processed_by' => $request->processed_by,
            'description' => $request->notes
        ]);

        // Log activity
        ActivityNotificationService::logAndNotify(
            auth()->user(),
            'process_payout',
            'Payout',
            $payout->id,
            "Payout #{$payout->id} for {$payout->educator->full_name}",
            ['status' => $oldStatus],
            ['status' => $request->status, 'processed_by' => $request->processed_by],
            "Processed payout #{$payout->id} - Status changed from {$oldStatus} to {$request->status}",
            ['amount' => $payout->amount, 'notes' => $request->notes]
        );

        // Update related earnings if payout is completed
        if ($request->status === 'completed') {
            Earning::where('payout_id', $payout->id)
                ->update([
                    'status' => 'paid',
                    'paid_at' => now()
                ]);

            // Send success email to educator
            try {
                EmailService::send(
                    $payout->educator->email,
                    new EducatorPayoutMail($payout, true),
                    'emails'
                );
            } catch (\Exception $e) {
                \Log::error('Failed to send payout success email: ' . $e->getMessage());
            }
        } elseif ($request->status === 'failed') {
            // Send failure email to educator
            try {
                EmailService::send(
                    $payout->educator->email,
                    new EducatorPayoutMail($payout, false),
                    'emails'
                );
            } catch (\Exception $e) {
                \Log::error('Failed to send payout failure email: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Payout processed successfully');
    }

    public function updateEarningStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,paid,cancelled'
        ]);

        $earning = Earning::findOrFail($id);
        $earning->update(['status' => $request->status]);

        if ($request->status === 'paid') {
            $earning->update(['paid_at' => now()]);
        }

        return back()->with('success', 'Earning status updated successfully');
    }

    public function bulkUpdateEarnings(Request $request)
    {
        $request->validate([
            'earning_ids' => 'required|array',
            'status' => 'required|in:pending,approved,paid,cancelled'
        ]);

        Earning::whereIn('id', $request->earning_ids)
            ->update(['status' => $request->status]);

        if ($request->status === 'paid') {
            Earning::whereIn('id', $request->earning_ids)
                ->update(['paid_at' => now()]);
        }

        return back()->with('success', 'Earnings updated successfully');
    }

    public function createPayout(Request $request)
    {
        $request->validate([
            'educator_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        Payout::create([
            'educator_id' => $request->educator_id,
            'amount' => $request->amount,
            'status' => 'pending',
            'description' => $request->description
        ]);

        return back()->with('success', 'Payout created successfully');
    }

    // Generate upcoming payouts based on approved earnings
    public function generateUpcomingPayouts()
    {
        // Get all approved earnings that haven't been paid yet and don't have a payout
        $approvedEarnings = Earning::where('status', 'approved')
            ->whereNull('payout_id')
            ->with('educator')
            ->get()
            ->groupBy('educator_id');

        $generatedPayouts = [];

        foreach ($approvedEarnings as $educatorId => $earnings) {
            $totalAmount = $earnings->sum('net_amount');

            if ($totalAmount > 0) {
                $payout = Payout::create([
                    'educator_id' => $educatorId,
                    'amount' => $totalAmount,
                    'status' => 'pending',
                    'description' => 'Auto-generated payout for ' . $earnings->count() . ' earnings'
                ]);

                // Link earnings to this payout
                Earning::whereIn('id', $earnings->pluck('id'))
                    ->update(['payout_id' => $payout->id]);

                $generatedPayouts[] = $payout;
            }
        }

        return back()->with('success', 'Generated ' . count($generatedPayouts) . ' upcoming payouts');
    }

    // View upcoming payouts (pending payouts)
    public function upcomingPayouts(Request $request)
    {
        $query = Payout::with('educator')
            ->where('status', 'pending');

        // Filter by educator
        if ($request->filled('educator_id')) {
            $query->where('educator_id', $request->educator_id);
        }

        // Filter by amount range
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        // Search by educator name
        if ($request->filled('q')) {
            $search = $request->q;
            $query->whereHas('educator', function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%']);
            });
        }

        $upcomingPayouts = $query->latest()->paginate(15);

        // Get summary statistics
        $totalUpcomingAmount = Payout::where('status', 'pending')->sum('amount');
        $totalUpcomingCount = Payout::where('status', 'pending')->count();

        // Get educators for filter dropdown
        $educators = User::where('role', 'educator')->select('id', 'first_name', 'last_name')->get();

        return view('admin.payouts.upcoming', compact(
            'upcomingPayouts',
            'totalUpcomingAmount',
            'totalUpcomingCount',
            'educators'
        ));
    }

    // Release/process multiple payouts
    public function releasePayouts(Request $request)
    {
        $request->validate([
            'payout_ids' => 'required|array',
            'payout_ids.*' => 'exists:payouts,id',
            'processed_by' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $payouts = Payout::whereIn('id', $request->payout_ids)
            ->where('status', 'pending')
            ->get();

        $processedCount = 0;

        foreach ($payouts as $payout) {
            $oldStatus = $payout->status;
            $payout->update([
                'status' => 'completed',
                'processed_at' => now(),
                'processed_by' => $request->processed_by,
                'description' => $request->notes ?: $payout->description
            ]);

            // Log activity for each payout
            ActivityNotificationService::logAndNotify(
                auth()->user(),
                'bulk_release_payout',
                'Payout',
                $payout->id,
                "Bulk payout release #{$payout->id} for {$payout->educator->full_name}",
                ['status' => $oldStatus],
                ['status' => 'completed', 'processed_by' => $request->processed_by],
                "Bulk released payout #{$payout->id} - Amount: ${$payout->amount}",
                ['amount' => $payout->amount, 'bulk_operation' => true]
            );

            // Update related earnings to paid status
            Earning::where('payout_id', $payout->id)
                ->update([
                    'status' => 'paid',
                    'paid_at' => now()
                ]);

            // Send success email to educator
            try {
                EmailService::send(
                    $payout->educator->email,
                    new EducatorPayoutMail($payout, true),
                    'emails'
                );
            } catch (\Exception $e) {
                \Log::error('Failed to send bulk payout email: ' . $e->getMessage());
            }

            $processedCount++;
        }

        return back()->with('success', 'Successfully released ' . $processedCount . ' payouts');
    }

    // Release single payout
    public function releasePayout(Request $request, Payout $payout)
    {
        $request->validate([
            'processed_by' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        if ($payout->status !== 'pending') {
            return back()->with('error', 'Only pending payouts can be released');
        }

        $oldStatus = $payout->status;
        $payout->update([
            'status' => 'completed',
            'processed_at' => now(),
            'processed_by' => $request->processed_by,
            'description' => $request->notes ?: $payout->description
        ]);

        // Log activity
        ActivityNotificationService::logAndNotify(
            auth()->user(),
            'release_payout',
            'Payout',
            $payout->id,
            "Payout #{$payout->id} for {$payout->educator->full_name}",
            ['status' => $oldStatus],
            ['status' => 'completed', 'processed_by' => $request->processed_by],
            "Released payout #{$payout->id} - Amount: ${$payout->amount}",
            ['amount' => $payout->amount, 'notes' => $request->notes]
        );

        // Update related earnings to paid status
        Earning::where('payout_id', $payout->id)
            ->update([
                'status' => 'paid',
                'paid_at' => now()
            ]);

        // Send success email to educator
        try {
            EmailService::send(
                $payout->educator->email,
                new EducatorPayoutMail($payout, true),
                'emails'
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send payout release email: ' . $e->getMessage());
        }

        return back()->with('success', 'Payout released successfully');
    }

    // Get payout details with earnings
    public function getPayoutDetails(Payout $payout)
    {
        $payout->load(['educator', 'earning']);
        $earnings = Earning::where('payout_id', $payout->id)
            ->with(['course', 'session'])
            ->get();

        return response()->json([
            'payout' => $payout,
            'earnings' => $earnings,
            'total_earnings' => $earnings->count(),
            'total_amount' => $earnings->sum('net_amount')
        ]);
    }
}
