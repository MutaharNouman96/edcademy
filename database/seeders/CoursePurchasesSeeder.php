<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\CoursePurchase;
use Faker\Factory as Faker;

class CoursePurchasesSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Fetch students and courses
        $students = User::where('role', 'student')->get();
        $courses  = Course::all();

        if ($students->count() == 0 || $courses->count() == 0) {
            return;
        }

        foreach ($students as $student) {

            // Each student buys 1–4 random courses
            $purchasedCourses = $courses->random(rand(1, 5));

            foreach ($purchasedCourses as $course) {

                CoursePurchase::create([
                    'student_id'      => $student->id,
                    'course_id'       => $course->id,
                    'status'          => 'completed',
                    'payment_status'  => 'paid',
                    'payment_method'  => $faker->randomElement(['stripe', 'paypal']),
                    'payment_id'      => strtoupper($faker->bothify('PAY-#######')),
                    'is_active'       => 1,
                    'created_at' => $faker->dateTimeBetween('-30 days', 'now')
                ]);
            }
        }
    }
}
