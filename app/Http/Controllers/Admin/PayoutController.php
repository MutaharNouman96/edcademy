<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Earning;
use App\Models\Payout;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    //
    public function index() {}

    public function show(Payout $payout) {}

    public function process(Payout $payout, Request $request)
    {
        Earning::where('educator_id', $payout->educator_id)
            ->where('id', $payout->earning_id)
            ->where('status', 'approved')
            ->update([
                'status' => 'paid',
                'payout_id' => $payout->id,
                'paid_at' => now()
            ]);
    }
}
