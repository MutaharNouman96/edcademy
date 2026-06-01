<?php

namespace App\Observers;

use App\Models\Earning;
use App\Models\EducatorPayment;
use App\Models\Payment;

class PaymentObserver
{
    //
    public function updated(EducatorPayment $educatorPayment)
    {
        if ($educatorPayment->status === 'completed') {

            $commissionRate = config('platform.commission_rate', 0.15); // 15% default
            $commission = $educatorPayment->gross_amount * $commissionRate;
            $net = $educatorPayment->gross_amount - $commission;

            Earning::create([
                'educator_id' => $educatorPayment->educator_id,
                'payment_id' => $educatorPayment->id,
                'gross_amount' => $educatorPayment->gross_amount,
                'platform_commission' => $commission,
                'net_amount' => $net,
                'currency' => $educatorPayment->currency,
                'status' => 'approved',

                'earned_at' => now(),
                'description' => "Earning generated from payment #{$educatorPayment->id}",
            ]);
        }
    }
}
