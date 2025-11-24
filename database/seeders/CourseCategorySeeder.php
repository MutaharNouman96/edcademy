<?php

namespace Database\Seeders;

use App\Models\CourseCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseCategorySeeder extends Seeder
{
        public function run()
    {
        $categories = [

            // 1. Academics
            'Academics' => [
                'Mathematics',
                'Science',
                'Physics',
                'Chemistry',
                'Biology',
                'English Language',
                'English Literature',
                'History',
                'Geography',
                'Computer Science',
                'Islamic Studies',
                'Social Studies',
            ],

            // 2. High School / Exam Prep
            'Exam Preparation' => [
                'SAT / ACT Prep',
                'IGCSE',
                'GCSE',
                'IB Curriculum',
                'A-Level Subjects',
                'AP Subjects',
                'Entrance Test Preparation',
            ],

            // 3. University & Academic Skills
            'University Skills' => [
                'Engineering Fundamentals',
                'Economics',
                'Accounting',
                'Finance',
                'Business Administration',
                'Academic Writing',
                'Research Skills',
                'Thesis Support',
            ],

            // 4. Professional Skills
            'Professional Skills' => [
                'Project Management',
                'Communication Skills',
                'Digital Marketing',
                'SEO / SEM',
                'Sales & CRM',
                'Leadership Training',
                'Entrepreneurship',
                'Customer Service',
                'HR & Recruitment',
            ],

            // 5. Technology & Coding
            'Technology & Coding' => [
                'Web Development',
                'HTML & CSS',
                'JavaScript',
                'Python',
                'PHP / Laravel',
                'React / Next.js',
                'Mobile App Development',
                'UI/UX Design',
                'Cybersecurity Basics',
                'Cloud Computing',
            ],

            // 6. Creative & Design
            'Design & Creativity' => [
                'Graphic Design',
                'Canva Mastery',
                'Adobe Photoshop',
                'Adobe Illustrator',
                'Video Editing',
                'Photography',
                'Animation',
                'Motion Graphics',
            ],

            // 7. Language Learning
            'Languages' => [
                'English',
                'Arabic',
                'French',
                'Urdu',
                'Spanish',
                'IELTS Prep',
                'TOEFL Prep',
                'Business English',
            ],

            // 8. Personal Development
            'Personal Development' => [
                'Time Management',
                'Productivity',
                'Confidence Building',
                'Mindfulness',
                'Public Speaking',
                'Study Skills',
                'Stress Management',
                'Goal Setting',
            ],

            // 9. Business & Finance
            'Business & Finance' => [
                'Startup Building',
                'E-commerce',
                'Personal Finance',
                'Investing',
                'Real Estate Investing',
                'Marketing Fundamentals',
                'Supply Chain Basics',
            ],

            // 10. Lifestyle & Hobbies
            'Lifestyle & Hobbies' => [
                'Cooking & Baking',
                'Fitness & Health',
                'Yoga & Meditation',
                'Music',
                'Beauty & Skincare',
                'Makeup Artistry',
                'DIY Crafts',
            ],

            // 11. Educator Resources
            'Educator Resources' => [
                'Lesson Planning',
                'Classroom Management',
                'Teaching Strategies',
                'Curriculum Development',
                'Online Teaching Skills',
                'Special Education Basics',
                'AI for Teaching',
            ],
        ];

        foreach ($categories as $parent => $subs) {

            // Create parent category
            $parentCat = CourseCategory::firstOrCreate(
                ['name' => $parent],
                [
                    'slug' => Str::slug($parent),
                    'parent_id' => null,
                ]
            );

            // Create subcategories
            foreach ($subs as $sub) {
                CourseCategory::firstOrCreate(
                    ['name' => $sub],
                    [
                        'slug' => Str::slug($sub),
                        'parent_id' => $parentCat->id,
                    ]
                );
            }
        }

        echo "✔ Categories seeded successfully.\n";
    }

    
}
