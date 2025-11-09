<?php

namespace App\Observers;

use App\Models\Earning;
use App\Models\Payment;

class PaymentObserver
{
    //
    public function updated(Payment $payment)
    {
        if ($payment->status === 'completed') {

            $commissionRate = config('platform.commission_rate', 0.15); // 15% default
            $commission = $payment->gross_amount * $commissionRate;
            $net = $payment->gross_amount - $commission;

            Earning::create([
                'educator_id' => $payment->educator_id,
                'payment_id' => $payment->id,
                'gross_amount' => $payment->gross_amount,
                'platform_commission' => $commission,
                'net_amount' => $net,
                'currency' => $payment->currency,
                'status' => 'approved',
                'source_type' => $payment->session_id ? 'session' : ($payment->course_id ? 'course' : 'resource'),
                'session_id' => $payment->session_id ?? null,
                'course_id' => $payment->course_id ?? null,
                'course_resource_id' => $payment->course_resource_id ?? null,
                'earned_at' => now(),
                'description' => "Earning generated from payment #{$payment->id}",
            ]);
        }
    }
}
