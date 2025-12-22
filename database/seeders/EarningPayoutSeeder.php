<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Earning;
use App\Models\Payout;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\SessionCall;
use App\Models\Payment;
use Illuminate\Support\Str;

class EarningPayoutSeeder extends Seeder
{
    public function run()
    {
        // ----------------------------------------
        // FETCH REQUIRED DATA
        // ----------------------------------------
        $educators = User::where('role', 'educator')->pluck('id')->toArray();
        $courses   = Course::pluck('id')->toArray();
        $lessons   = Lesson::pluck('id')->toArray();
        $sessions  = SessionCall::pluck('id')->toArray();
        $payments  = Payment::pluck('id')->toArray();

        if (empty($educators)) {
            dd("No educators found — seed users first.");
        }

        // ----------------------------------------
        // STEP 1: Generate Earnings (NO payout_id yet)
        // ----------------------------------------
        $earningIds = [];

        for ($i = 0; $i < 300; $i++) {

            $educatorId = $educators[array_rand($educators)];
            $sourceType = collect(['course', 'session', 'resource'])->random();

            $courseId = $sourceType === 'course' ? ($courses[array_rand($courses)] ?? null) : null;
            $sessionId = $sourceType === 'session' ? ($sessions[array_rand($sessions)] ?? null) : null;
            $resourceId = $sourceType === 'resource' ? ($lessons[array_rand($lessons)] ?? null) : null;

            // Payment optionally linked
            $paymentId = collect([null, $payments[array_rand($payments)] ?? null])->random();

            $gross = rand(5000, 50000) / 100; // 50–500
            $commission = $gross * 0.10;
            $net = $gross - $commission;

            $earning = Earning::create([
                'educator_id'         => $educatorId,
                'payment_id'          => $paymentId,
                'payout_id'           => null,  // filled later
                'course_id'           => $courseId,
                'session_id'          => $sessionId,
                'course_resource_id'  => $resourceId,
                'gross_amount'        => $gross,
                'platform_commission' => $commission,
                'net_amount'          => $net,
                'currency'            => 'USD',
                'source_type'         => $sourceType,
                'status'              => collect(['pending', 'approved', 'paid'])->random(),
                'description'         => Str::random(40),
                'earned_at'           => now()->subDays(rand(0, 30)),
                'paid_at'             => rand(0, 1) ? now()->subDays(rand(0, 15)) : null,
            ]);

            $earningIds[] = $earning->id;
        }

        // ----------------------------------------
        // STEP 2: Generate Payouts (Linked to Earnings)
        // ----------------------------------------
        $payoutIds = [];

        for ($i = 0; $i < 100; $i++) {

            $educatorId = $educators[array_rand($educators)];
            $earningId  = collect([null, $earningIds[array_rand($earningIds)]])->random();

            $amount = rand(5000, 50000) / 100;
            $status = collect(['pending', 'processing', 'completed', 'cancelled'])->random();

            $payout = Payout::create([
                'educator_id'  => $educatorId,
                'amount'       => $amount,
                'status'       => $status,
                'processed_at' => in_array($status, ['processing', 'completed'])
                    ? now()->subDays(rand(1, 20))
                    : null,
                'processed_by' => in_array($status, ['processing', 'completed'])
                    ? 'system-admin'
                    : null,
                'description'  => Str::random(50),
                'earning_id'   => $earningId,
            ]);

            $payoutIds[] = $payout->id;

            // If payout is linked to earning, update earning
            if ($earningId) {
                Earning::where('id', $earningId)->update([
                    'payout_id' => $payout->id
                ]);
            }
        }
    }
}
