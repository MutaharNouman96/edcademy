<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Session;
use App\Models\SessionUsers;
use App\Models\Schedule;
use App\Models\CoursePurchase; // Ensure this model exists
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SessionAndScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // Get all educators
        $educators = User::where('role', 'educator')->get();

        foreach ($educators as $educator) {

            // Fetch students who purchased this educator’s courses
            $students = CoursePurchase::whereIn('course_id', function ($query) use ($educator) {
                return $query->select('id')->from('courses')->where('user_id', $educator->id);
            })
                ->pluck('student_id')
                ->unique();

            if ($students->count() == 0) {
                continue; // Skip educators without student purchases
            }

            // Example: create 3 sessions for every educator
            for ($i = 1; $i <= 3; $i++) {

                DB::beginTransaction();

                try {
                    $start = Carbon::now()->addDays(rand(1, 10))->setTime(rand(9, 16), 0, 0);
                    $end = (clone $start)->addHour();

                    // CREATE SESSION
                    $session = Session::create([
                        'title'        => "Demo Session $i for " . $educator->name,
                        'educator_id'  => $educator->id,
                        'start_time'   => $start,
                        'end_time'     => $end,
                        'meeting_link' => "https://meet.example.com/session-$i",
                        'status'       => 'booked',
                        'is_paid'      => 1,
                        'price'        => 0,
                    ]);

                    // ADD SESSION TO EDUCATOR’S SCHEDULE
                    Schedule::create([
                        'user_id'    => $educator->id,
                        'title'      => "Teaching Session: Demo $i",
                        'description' => "Scheduled demo session with your students.",
                        'start_date' => $start,
                        'end_date'   => $end,
                        'session_id' => $session->id,
                    ]);

                    // ATTACH STUDENTS
                    foreach ($students as $studentId) {

                        // Add to pivot (session_users)
                        SessionUsers::create([
                            'session_id'     => $session->id,
                            'user_id'        => $studentId,
                            'role'           => 'student',
                            'status'         => 'booked',
                            'payment_status' => 'completed',
                            'is_active'      => 1,
                        ]);

                        // ADD TO STUDENT SCHEDULE
                        Schedule::create([
                            'user_id'    => $studentId,
                            'title'      => "Session: " . $session->title,
                            'description' => "Session with educator " . $educator->name,
                            'start_date' => $start,
                            'end_date'   => $end,
                            'session_id' => $session->id,
                        ]);
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    dump("Failed Seeding Session: " . $e->getMessage());
                }
            }
        }

        echo "Sessions + Schedules seeded successfully.\n";
    }
}
