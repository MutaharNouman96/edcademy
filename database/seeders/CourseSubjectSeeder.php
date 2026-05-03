<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseCategory;
use App\Models\Subject;
use Illuminate\Support\Str;

class CourseSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear all subjects
        Subject::query()->delete();
        // Clear all categories
        CourseCategory::query()->delete();

        $categoriesWithSubjects = [
            'Mathematics' => ['Mathematics', 'Geometry', 'Algebra', 'Calculus', 'Statistics', 'Probability', 'Linear Algebra'],
            'Science' => ['Physics', 'Chemistry', 'Biology', 'Geology', 'Astronomy', 'Environmental Science'],
            'Business' => ['Accounting', 'Marketing', 'Finance', 'Entrepreneurship', 'Economics', 'Business Law', 'Management'],
            'Arts' => ['Painting', 'Sculpture', 'Music', 'Dance', 'Drama', 'Photography', 'Film Studies'],
            'Languages' => ['English', 'Spanish', 'French', 'German', 'Mandarin', 'Japanese', 'Russian'],
            'Test Preparation' => ['SAT', 'GRE', 'GMAT', 'TOEFL', 'IELTS', 'ACT', 'LSAT'],
            'Technology' => ['Computer Science', 'Programming', 'Information Systems', 'Cybersecurity', 'Data Science', 'Networking', 'Artificial Intelligence'],
        ];

        foreach ($categoriesWithSubjects as $categoryName => $subjects) {
            // Create category
            $categoryModel = CourseCategory::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
                'parent_id' => null,
            ]);
            // Create subjects for category
            foreach ($subjects as $subject) {
                Subject::create([
                    'name' => $subject,
                    'slug' => Str::slug($subject),
                    'category_id' => $categoryModel->id,
                ]);
            }
        }
    }
}
