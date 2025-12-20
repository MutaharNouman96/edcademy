<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User; // Make sure to use your User model path
use Illuminate\Support\Str;

class EducatorProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Get all Users who have the 'educator' role
        // Adjust the WHERE clause based on how you store roles (e.g., role_id, a separate table, or an enum/string column)
        $educatorUsers = User::where('role', 'educator')->get();

        if ($educatorUsers->isEmpty()) {
            // Optional: Log a warning or message if no educators are found
            // echo "No users found with role 'educator'. Skipping profile creation.\n";
            return;
        }

        // 2. Prepare data for insertion
        $profilesToInsert = [];
        $teachingStyles = ['visual', 'auditory', 'kinesthetic', 'blended'];
        $subjects = ['Mathematics', 'Science', 'English', 'History', 'Programming'];

        foreach ($educatorUsers as $user) {
            $profilesToInsert[] = [
                'user_id' => $user->id,
                'primary_subject' => $subjects[array_rand($subjects)], // Random Subject
                'teaching_levels' => json_encode(['High School', 'College']), // Store as JSON string for longtext/JSON
                'hourly_rate' => rand(20, 150) + (rand(0, 99) / 100), // Random rate between 20.00 and 150.99
                'certifications' => Str::random(10) . ' Certification',
                'preferred_teaching_style' => $teachingStyles[array_rand($teachingStyles)],
                // Paths can be null/empty for seeding
                'govt_id_path' => null,
                'degree_proof_path' => null,
                'intro_video_path' => null,
                // Default values based on your schema
                'consent_verified' => 0,
                'status' => 'pending', // Default status
                'featured' => 0,
                // New columns from your migration
                'location' => 'City, Country',
                'bio' => 'Hello! I am a passionate educator focused on ' . Str::lower($subjects[array_rand($subjects)]) . '.',
                // Timestamps
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 3. Insert the data into the educator_profiles table
        DB::table('educator_profiles')->insert($profilesToInsert);
    }
}
