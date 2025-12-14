<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all educator users
        $educatorUsers = User::where('role', 'educator')->pluck('id')->toArray();

        if (empty($educatorUsers)) {
            $this->command->info('No educator users found. Please seed educators first.');
            return;
        }

        for ($i = 0; $i < 20; $i++) { // Create 20 courses
                        $title = $faker->sentence(3);

            Course::create([
                
                'user_id' => $faker->randomElement($educatorUsers),
                'title' => $title,
                'slug' => Str::slug($title),
                'description' => $faker->paragraph(rand(5, 15)),
                'subject' => $faker->word(),
                'level' => $faker->randomElement(['Beginner', 'Intermediate', 'Advanced']),
                'language' => $faker->randomElement(['English', 'Spanish', 'French', 'German']),
                'price' => $faker->randomFloat(2, 0, 500),
                'is_free' => $faker->boolean(),
                'duration' => $faker->numberBetween(1, 100) . ' ' . $faker->randomElement(['hours', 'days', 'weeks']),
                'difficulty' => $faker->randomElement(['beginner', 'intermediate', 'advanced']),
                'type' => $faker->randomElement(['module', 'video', 'live']),
                'schedule_date' => $faker->dateTimeBetween('now', '+1 year'),
                'thumbnail' => 'https://via.placeholder.com/150',
                'publish_option' => $faker->randomElement(['now', 'schedule', 'draft']),
                'publish_date' => $faker->dateTimeBetween('now', '+1 year'),
                'status' => $faker->randomElement(['draft', 'published', 'scheduled']),
                'drip' => $faker->boolean(),
                'drip_duration' => $faker->numberBetween(1, 30) . ' ' . $faker->randomElement(['days', 'weeks']),
                'created_at' => $faker->dateTimeThisYear(),
                'updated_at' => $faker->dateTimeThisYear(),
            ]);
        }
    }
}
