<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseReview;
use App\Models\CoursePurchase;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CourseReviewSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Fetch all course purchases
        $purchases = CoursePurchase::all();

        foreach ($purchases as $purchase) {
            // Randomly decide whether this purchase leaves a review
            if (rand(0, 1)) {
                CourseReview::create([
                    'course_id' => $purchase->course_id,
                    'student_id' => $purchase->student_id,
                    'rating' => $faker->numberBetween(3, 5), // rating between 3 and 5
                    'comment' => $faker->sentence(10),
                ]);
            }
        }
    }
}
