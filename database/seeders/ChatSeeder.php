<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\CoursePurchase;
use App\Models\Course;

class ChatSeeder extends Seeder
{
    public function run()
    {
        $purchases = CoursePurchase::all();

        foreach ($purchases as $purchase) {
            $studentId = $purchase->student_id;

            // Fetch educator from the course relation
            $course = Course::find($purchase->course_id);

            if (!$course || !$course->user_id) {
                continue; // skip invalid courses
            }

            $educatorId = $course->user_id;

            // Check if chat already exists (either direction)
            $chat = Chat::where(function ($q) use ($studentId, $educatorId) {
                $q->where('sender_id', $studentId)->where('receiver_id', $educatorId);
            })
                ->orWhere(function ($q) use ($studentId, $educatorId) {
                    $q->where('sender_id', $educatorId)->where('receiver_id', $studentId);
                })
                ->first();

            if (!$chat) {
                $chat = Chat::create([
                    'sender_id' => $studentId,
                    'receiver_id' => $educatorId,
                ]);
            }

            // OPTIONAL: Seed first welcome message (only if no messages exist)
            if ($chat->messages()->count() == 0) {
                ChatMessage::create([
                    'chat_id' => $chat->id,
                    'sender_id' => $educatorId,
                    'message' => 'Welcome! Feel free to ask any questions about this course.',
                    'type' => 'text',
                ]);
            }

            // 2. Add 20 random follow-up messages
            $faker = \Faker\Factory::create();

            for ($i = 0; $i < 50; $i++) {

                // Randomly pick the sender (student or educator)
                $sender = rand(0, 1) ? $studentId : $educatorId;

                ChatMessage::create([
                    'chat_id'   => $chat->id,
                    'sender_id' => $sender,
                    'message'   => $faker->sentence(rand(5, 25)),
                    'type'      => 'text',
                    'created_at' => now()->subMinutes(rand(1, 2000)),
                ]);
            }
        }
    }
}
