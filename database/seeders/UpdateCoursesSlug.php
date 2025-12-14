<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use Illuminate\Support\Str;

class UpdateCoursesSlug extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Get all courses from the database
        $courses = Course::all();

        // 2. Loop through each course
        foreach ($courses as $course) {
            // Check if the course has a title (optional, but recommended)
            if ($course->title) {
                // 3. Generate the slug from the title using the Str::slug() helper
                $slug = Str::slug($course->title);

                // 4. Update the slug attribute
                $course->slug = $slug;

                // 5. Save the updated model back to the database
                // Note: Laravel's Eloquent handles the SQL UPDATE statement here
                $course->save();
            }
        }
    }
}
