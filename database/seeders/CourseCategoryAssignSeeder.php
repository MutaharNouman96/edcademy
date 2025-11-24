<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CourseCategory;

class CourseCategoryAssignSeeder extends Seeder
{
    public function run()
    {
        // Get all category IDs
        $categoryIds = CourseCategory::pluck('id')->toArray();

        if (empty($categoryIds)) {
            echo "❌ No categories found. Seed categories first.\n";
            return;
        }

        // Assign category to each course randomly
        Course::chunk(100, function ($courses) use ($categoryIds) {
            foreach ($courses as $course) {
                $course->course_category_id = collect($categoryIds)->random();
                $course->save();
            }
        });

        echo "✔ All courses have been assigned a random category.\n";
    }
}
