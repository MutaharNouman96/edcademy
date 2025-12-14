<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use App\Models\Purchase;
use Faker\Factory as Faker;

class AllInOneDBSeeder extends Seeder
{
    public function run(): void
    {
        // --- USERS ---
        echo "Seeding users...\n";

        // Admin
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@edcademy.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now()
        ]);

        // Educators
        $educators = collect();
        for ($i = 1; $i <= 20; $i++) {
            $faker = Faker::create();
            $educators->push(User::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => "educator{$i}@edcademy.com",
                'password' => Hash::make('password123'),
                'role' => 'educator',
                'email_verified_at' => now()
            ]));
        }

        // Students
        $students = collect();
        for ($i = 1; $i <= 300; $i++) {
            $faker = Faker::create();
            $students->push(User::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => "student{$i}@edcademy.com",
                'password' => Hash::make('password123'),
                'role' => 'student',
                'email_verified_at' => now()
            ]));
        }

        // --- COURSES ---
        echo "Seeding courses...\n";

        $subjects = ['Math', 'Physics', 'Chemistry', 'Biology', 'English', 'Computer Science', 'Economics', 'Art', 'Geography', 'History', 'Sociology'];
        $levels = ['Beginner', 'Intermediate', 'Advanced'];
        $languages = ['English', 'Latin', 'German', 'Spanish', 'French'];

        $courses = collect();

        foreach ($educators as $educator) {
            $numCourses = rand(3, 6);
            for ($i = 1; $i <= $numCourses; $i++) {
                $title = fake()->sentence(3);
                $slug = Str::slug($title);
                $course = Course::create([
                    'user_id' => $educator->id,
                    'title' => $title,
                    'slug' => $slug,
                    'description' => fake()->paragraph(),
                    'subject' => fake()->randomElement($subjects),
                    'level' => fake()->randomElement($levels),
                    'language' => fake()->randomElement($languages),
                    'price' => fake()->randomFloat(2, 10, 100),
                    'is_free' => fake()->boolean(20),
                    'duration' => rand(4, 12),
                    'difficulty' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
                    'type' => fake()->randomElement(['module', 'video', 'live']),
                    'status' => 'published',
                    'publish_option' => 'now',
                ]);

                // --- SECTIONS & LESSONS ---
                $numSections = rand(2, 5);
                for ($s = 1; $s <= $numSections; $s++) {
                    $section = CourseSection::create([
                        'course_id' => $course->id,
                        'title' => 'Section ' . $s,
                        'order' => $s,
                    ]);

                    $numLessons = rand(3, 6);
                    for ($l = 1; $l <= $numLessons; $l++) {
                        Lesson::create([
                            'course_section_id' => $section->id,
                            'course_id' => $course->id,
                            'type' => 'video',
                            'title' => 'Lesson ' . $l,
                            'duration' => rand(5, 20),
                            'price' => 0,
                            'free' => fake()->boolean(30),
                            'status' => 'Published',
                            'preview' => fake()->boolean(20),
                            'notes' => fake()->sentence(),
                        ]);
                    }
                }

                $courses->push($course);
            }
        }

        // --- PURCHASES ---
        echo "Seeding purchases (student enrollments)...\n";

        foreach ($students as $student) {
            $randomCourses = $courses->random(rand(3, 10));
            foreach ($randomCourses as $course) {
                Purchase::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'status' => 'completed',
                    'payment_status' => 'paid',
                    'payment_method' => 'card',
                    'payment_id' => Str::uuid(),
                    'is_active' => 1,
                ]);
            }
        }

        echo "Seeding complete.\n";
    }
}
