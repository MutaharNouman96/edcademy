<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EducatorProfile;
use App\Models\User;

class EducatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Create 100 educators with profiles
        EducatorProfile::factory()
            ->count(100)
            ->create();

        // Optional: create one known test educator
        $user = User::factory()->educator()->create([
            'name' => 'Test Educator',
            'email' => 'educator@edcademy.com',
            'password' => bcrypt('password123'),
        ]);

        EducatorProfile::factory()->create([
            'user_id' => $user->id,
            'primary_subject' => 'Mathematics',
            'hourly_rate' => 35.00,
            'status' => 'approved',
            'consent_verified' => true,
            'certifications' => 'B.Ed, M.Sc. Mathematics',
            'preferred_teaching_style' => 'Interactive / Discussion-based',
        ]);
    }
}
