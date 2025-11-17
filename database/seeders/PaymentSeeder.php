<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Session;
use Illuminate\Support\Str;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        $students = User::where('role', 'student')->pluck('id')->toArray();
        $educators = User::where('role', 'educator')->pluck('id')->toArray();

        $courses  = Course::pluck('id')->toArray();
        $lessons  = Lesson::pluck('id')->toArray();
        $sessions = Session::pluck('id')->toArray();

        if (empty($students) || empty($educators)) {
            dd("Please seed users first (students & educators required).");
        }

        $paymentMethods = ['stripe', 'paypal', 'razorpay', 'cash'];

        // Seed 300 payment records
        for ($i = 0; $i < 300; $i++) {

            $studentId  = $students[array_rand($students)];
            $educatorId = $educators[array_rand($educators)];

            // Random type of purchase
            $purchaseType = collect(['course', 'session', 'resource'])->random();

            $courseId = null;
            $lessonId = null;
            $sessionId = null;

            if ($purchaseType === 'course' && $courses) {
                $courseId = $courses[array_rand($courses)];
            }

            if ($purchaseType === 'session' && $sessions) {
                $sessionId = $sessions[array_rand($sessions)];
            }

            if ($purchaseType === 'resource' && $lessons) {
                $lessonId = $lessons[array_rand($lessons)];
            }

            // Pricing calculations
            $gross = rand(1000, 10000) / 100; // 10 - 100
            $tax   = $gross * 0.05;          // 5% tax
            $commission = $gross * 0.10;     // platform 10%
            $net = $gross - $commission - $tax;

            Payment::create([
                'educator_id'        => $educatorId,
                'student_id'         => $studentId,

                'course_id'          => $courseId,
                'course_resource_id' => $lessonId,
                'session_id'         => $sessionId,

                'gross_amount'       => $gross,
                'tax_amount'         => $tax,
                'platform_commission' => $commission,
                'net_amount'         => $net,
                'currency'           => 'USD',

                'payment_method'     => $paymentMethods[array_rand($paymentMethods)],
                'transaction_id'     => strtoupper(Str::random(12)),
                'status'             => collect(['pending', 'completed', 'failed', 'refunded'])->random(),

                'notes'              => Str::random(40),
            ]);
        }
    }
}
