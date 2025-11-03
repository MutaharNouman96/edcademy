<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EducatorProfile>
 */
class EducatorProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subjects = ['Mathematics', 'Science', 'English', 'Computer Science', 'Languages', 'History'];
        $styles = ['Interactive / Discussion-based', 'Lecture / Presentation', 'Hands-on / Practical', 'Assessment-driven'];

        return [
            'user_id' => User::factory()->educator(),
            'primary_subject' => fake()->randomElement($subjects),
            'teaching_levels' => json_encode(fake()->randomElements(
                ['Elementary', 'Middle School', 'High School', 'College', 'Professional'],
                rand(1, 3)
            )),
            'hourly_rate' => fake()->randomFloat(2, 10, 100),
            'certifications' => fake()->sentence(8),
            'preferred_teaching_style' => fake()->randomElement($styles),
            'govt_id_path' => 'uploads/ids/sample_id.pdf',
            'degree_proof_path' => 'uploads/degrees/sample_degree.pdf',
            'intro_video_path' => fake()->optional()->url(),
            'consent_verified' => fake()->boolean(80),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
            'verified_at' => fake()->optional(0.6)->dateTimeBetween('-1 month', 'now'),
            'verified_by' => null, // can later link admin reviewer
        ];
    }
}
