<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Earning;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Session;
use App\Models\Payment;
use App\Models\Payout;
use Illuminate\Support\Str;

class EarningSeeder extends Seeder
{
    public function run()
    {
        // Educators
        $educators = User::where('role', 'educator')->pluck('id')->toArray();

        // Linking models
        $courses = Course::pluck('id')->toArray();
        $lessons = Lesson::pluck('id')->toArray();
        $sessions = Session::pluck('id')->toArray();
        $payments = Payment::pluck('id')->toArray();
        $payouts = Payout::pluck('id')->toArray();

        if (empty($educators)) {
            dd("No educators found — please seed users first.");
        }

        // Generate 300 earning records
        for ($i = 0; $i < 300; $i++) {

            $educatorId = $educators[array_rand($educators)];

            // Random earning type
            $sourceType = collect(['course', 'session', 'resource'])->random();

            // Attach correct linked item
            $courseId = null;
            $sessionId = null;
            $resourceId = null;

            if ($sourceType == 'course' && $courses) {
                $courseId = $courses[array_rand($courses)];
            } elseif ($sourceType == 'session' && $sessions) {
                $sessionId = $sessions[array_rand($sessions)];
            } elseif ($sourceType == 'resource' && $lessons) {
                $resourceId = $lessons[array_rand($lessons)];
            }

            // Amounts
            $gross = rand(5000, 50000) / 100; // 50–500
            $commission = $gross * 0.10;      // 10%
            $net = $gross - $commission;

            // Payment & payout linking (optional)
            $paymentId = collect([null, $payments[array_rand($payments)] ?? null])->random();
            $payoutId = collect([null, $payouts[array_rand($payouts)] ?? null])->random();

            Earning::create([
                'educator_id'       => $educatorId,
                'payment_id'        => $paymentId,
                'payout_id'         => $payoutId,

                'session_id'        => $sessionId,
                'course_id'         => $courseId,
                'course_resource_id' => $resourceId,

                'gross_amount'        => $gross,
                'platform_commission' => $commission,
                'net_amount'          => $net,
                'currency'            => 'USD',

                'source_type'         => $sourceType,
                'status'              => collect(['pending', 'approved', 'paid'])->random(),

                'description' => Str::random(40),

                'earned_at' => now()->subDays(rand(0, 30)),
                'paid_at'   => rand(0, 1) ? now()->subDays(rand(0, 15)) : null,
            ]);
        }
    }
}
