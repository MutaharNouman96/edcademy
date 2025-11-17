<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LessonVideoViews;
use App\Models\User;
use App\Models\Lesson;


class LessonVideoViewSeeder extends Seeder
{
    public function run()
    {
        $users = User::where("role" , "student")->pluck('id')->toArray();
        $lessons = Lesson::pluck('id')->toArray();

        if (empty($users) || empty($lessons)) {
            dd("Users or Lessons table is empty. Seed them first.");
        }

        for ($i = 0; $i < 500; $i++) {

            LessonVideoViews::create([
                'user_id' => $users[array_rand($users)],
                'lesson_id' => $lessons[array_rand($lessons)],
                'watch_time' => rand(10, 3600), // seconds
                'completed' => rand(0, 100),   // percent
                'liked' => collect(['1', '0', null])->random(),
                'created_at' => now()->subDays(rand(1, 60))->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
