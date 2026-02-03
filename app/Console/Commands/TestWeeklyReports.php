<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\EmailService;
use App\Mail\WeeklyStudentReportMail;
use Carbon\Carbon;

class TestWeeklyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:weekly-reports {email? : Specific student email to test with}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test weekly report to a specific student or all students';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');

        if ($email) {
            // Send to specific student
            $student = User::where('email', $email)->where('role', 'student')->first();

            if (!$student) {
                $this->error("Student with email {$email} not found.");
                return Command::FAILURE;
            }

            $this->sendTestReport($student);
            $this->info("Test weekly report sent to {$email}");

        } else {
            // Send to a few test students
            $students = User::where('role', 'student')
                ->where('email_verified_at', '!=', null)
                ->limit(3)
                ->get();

            if ($students->isEmpty()) {
                $this->error("No verified students found to test with.");
                return Command::FAILURE;
            }

            foreach ($students as $student) {
                $this->sendTestReport($student);
                $this->line("Test weekly report sent to {$student->email}");
            }

            $this->info("Sent test reports to {$students->count()} students");
        }

        return Command::SUCCESS;
    }

    /**
     * Send a test report with sample data
     */
    private function sendTestReport(User $student)
    {
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();

        // Generate comprehensive test data
        $reportData = [
            'week_start' => $weekStart,
            'week_end' => $weekEnd,
            'courses_enrolled' => rand(2, 5),
            'lessons_completed' => rand(5, 15),
            'hours_learned' => rand(8, 20),
            'current_streak' => rand(3, 7),
            'course_progress' => [
                [
                    'title' => 'Advanced Web Development',
                    'progress_percentage' => 75,
                    'lessons_completed' => 12,
                    'total_lessons' => 16,
                    'last_accessed' => Carbon::now()->subDays(2),
                ],
                [
                    'title' => 'Python for Data Science',
                    'progress_percentage' => 45,
                    'lessons_completed' => 9,
                    'total_lessons' => 20,
                    'last_accessed' => Carbon::now()->subDays(1),
                ],
            ],
            'weekly_activity' => [
                [
                    'icon' => '📖',
                    'title' => 'Completed React Advanced Patterns',
                    'description' => 'Mastered hooks, context, and performance optimization',
                ],
                [
                    'icon' => '🎥',
                    'title' => 'Attended Live Q&A Session',
                    'description' => 'Got answers to complex database design questions',
                ],
                [
                    'icon' => '🏆',
                    'title' => 'Earned Achievement Badge',
                    'description' => 'Completed 10 lessons in a single week!',
                ],
            ],
            'upcoming_sessions' => [
                [
                    'title' => '1-on-1 with Sarah Johnson',
                    'description' => 'Advanced JavaScript concepts review',
                    'date' => Carbon::now()->addDays(2)->format('M j, Y'),
                    'time' => '3:00 PM',
                ],
                [
                    'title' => 'Group Study Session',
                    'description' => 'Python algorithms practice',
                    'date' => Carbon::now()->addDays(5)->format('M j, Y'),
                    'time' => '6:30 PM',
                ],
            ],
            'achievements' => [
                [
                    'badge' => '🔥',
                    'title' => '5-Day Learning Streak',
                    'description' => 'Consistent learning pays off!',
                ],
                [
                    'badge' => '📚',
                    'title' => 'Knowledge Seeker',
                    'description' => 'Completed 50 total lessons across all courses',
                ],
            ],
            'recommendations' => [
                [
                    'icon' => '🚀',
                    'title' => 'Next Level: Full-Stack Development',
                    'description' => 'Based on your progress, you\'re ready for full-stack development. This course combines frontend and backend skills.',
                    'link' => route('web.courses'),
                ],
                [
                    'icon' => '👥',
                    'title' => 'Join Advanced Study Group',
                    'description' => 'Connect with other advanced learners in our exclusive community. Share knowledge and solve complex problems together.',
                    'link' => '#',
                ],
                [
                    'icon' => '🎯',
                    'title' => 'Set Weekly Goals',
                    'description' => 'Set specific learning goals for next week. Research shows that goal-setting improves learning outcomes by 20%.',
                    'link' => route('student.dashboard'),
                ],
            ],
        ];

        try {
            EmailService::send(
                $student->email,
                new WeeklyStudentReportMail($student, $reportData),
                'emails'
            );
        } catch (\Exception $e) {
            $this->error("Failed to send test report to {$student->email}: " . $e->getMessage());
        }
    }
}
