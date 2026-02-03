<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Booking;
use App\Models\UserPurchasedItem;
use App\Services\EmailService;
use App\Mail\WeeklyStudentReportMail;
use Carbon\Carbon;

class SendWeeklyStudentReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:send-weekly-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly learning reports to all active students';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating and sending weekly student reports...');

        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();

        // Get all active students (those who have logged in within the last month)
        $students = User::where('role', 'student')
            ->where('email_verified_at', '!=', null)
            ->where('updated_at', '>=', Carbon::now()->subMonth())
            ->get();

        $this->info("Found {$students->count()} active students to send reports to");

        $sentCount = 0;

        foreach ($students as $student) {
            try {
                $reportData = $this->generateStudentReportData($student, $weekStart, $weekEnd);

                // Only send if there's meaningful activity
                if ($this->hasMeaningfulActivity($reportData)) {
                    EmailService::send(
                        $student->email,
                        new WeeklyStudentReportMail($student, $reportData),
                        'emails'
                    );

                    $sentCount++;
                    $this->line("Sent weekly report to: {$student->email}");
                } else {
                    $this->line("Skipped {$student->email} - no significant activity");
                }

            } catch (\Exception $e) {
                $this->error("Failed to send report to {$student->email}: " . $e->getMessage());
            }
        }

        $this->info("Successfully sent {$sentCount} weekly reports");
        return Command::SUCCESS;
    }

    /**
     * Generate comprehensive report data for a student
     */
    private function generateStudentReportData(User $student, Carbon $weekStart, Carbon $weekEnd): array
    {
        // Basic stats
        $coursesEnrolled = UserPurchasedItem::where('user_id', $student->id)
            ->where('active', true)
            ->whereHas('order', function($q) {
                $q->where('status', 'paid');
            })
            ->distinct('purchasable_id')
            ->count();

        // Mock data for demonstration - replace with actual tracking logic
        $reportData = [
            'week_start' => $weekStart,
            'week_end' => $weekEnd,
            'courses_enrolled' => $coursesEnrolled,
            'lessons_completed' => rand(2, 8), // Replace with actual lesson completion tracking
            'hours_learned' => rand(3, 12), // Replace with actual time tracking
            'current_streak' => rand(1, 7), // Replace with actual streak calculation
        ];

        // Course progress (mock data - replace with real progress tracking)
        $reportData['course_progress'] = $this->getCourseProgress($student);

        // Weekly activity (mock data - replace with real activity logging)
        $reportData['weekly_activity'] = $this->getWeeklyActivity($student, $weekStart, $weekEnd);

        // Upcoming sessions
        $reportData['upcoming_sessions'] = $this->getUpcomingSessions($student);

        // Achievements (mock data - replace with real achievement system)
        $reportData['achievements'] = $this->getWeeklyAchievements($student, $weekStart, $weekEnd);

        // Recommendations (mock data - replace with AI/ML recommendations)
        $reportData['recommendations'] = $this->getPersonalizedRecommendations($student);

        return $reportData;
    }

    /**
     * Get course progress data for the student
     */
    private function getCourseProgress(User $student): array
    {
        // Get purchased courses with mock progress data
        $purchasedItems = UserPurchasedItem::where('user_id', $student->id)
            ->where('active', true)
            ->where('model', 'App\Models\Course')
            ->with('order')
            ->get();

        $courseProgress = [];

        foreach ($purchasedItems->take(3) as $item) { // Limit to 3 courses for email
            if ($item->model === 'App\Models\Course') {
                $course = \App\Models\Course::find($item->item_id);
                if ($course) {
                    // Mock progress - replace with actual progress tracking
                    $progressPercentage = rand(10, 95);
                    $lessonsCompleted = round(($progressPercentage / 100) * $course->lessons->count());

                    $courseProgress[] = [
                        'title' => $course->title,
                        'progress_percentage' => $progressPercentage,
                        'lessons_completed' => $lessonsCompleted,
                        'total_lessons' => $course->lessons->count(),
                        'last_accessed' => Carbon::now()->subDays(rand(1, 7)),
                    ];
                }
            }
        }

        return $courseProgress;
    }

    /**
     * Get weekly activity data
     */
    private function getWeeklyActivity(User $student, Carbon $weekStart, Carbon $weekEnd): array
    {
        // Mock activity data - replace with real activity logging
        $activities = [
            [
                'icon' => '📖',
                'title' => 'Completed Advanced JavaScript Module',
                'description' => 'Finished 3 lessons on async programming',
            ],
            [
                'icon' => '🎥',
                'title' => 'Watched Live Session',
                'description' => 'Attended "React Best Practices" with Sarah Johnson',
            ],
            [
                'icon' => '🏆',
                'title' => 'Earned Certificate',
                'description' => 'Completed "Python for Beginners" course',
            ],
        ];

        return array_slice($activities, 0, rand(1, 3));
    }

    /**
     * Get upcoming sessions for the student
     */
    private function getUpcomingSessions(User $student): array
    {
        $upcomingSessions = Booking::where('student_id', $student->id)
            ->where('status', 'confirmed')
            ->where('date', '>=', Carbon::today())
            ->where('date', '<=', Carbon::today()->addDays(14))
            ->with('educator')
            ->orderBy('date')
            ->orderBy('time')
            ->take(3)
            ->get();

        $sessions = [];
        foreach ($upcomingSessions as $booking) {
            $sessions[] = [
                'title' => 'Session with ' . $booking->educator->full_name,
                'description' => $booking->subject ?? 'Tutoring Session',
                'date' => $booking->date->format('M j, Y'),
                'time' => $booking->time,
            ];
        }

        return $sessions;
    }

    /**
     * Get weekly achievements
     */
    private function getWeeklyAchievements(User $student, Carbon $weekStart, Carbon $weekEnd): array
    {
        // Mock achievements - replace with real achievement system
        $achievements = [
            [
                'badge' => '🔥',
                'title' => '7-Day Learning Streak',
                'description' => 'Learned every day this week!',
            ],
            [
                'badge' => '📚',
                'title' => 'Course Completed',
                'description' => 'Finished "Web Development Fundamentals"',
            ],
            [
                'badge' => '⭐',
                'title' => 'First Review Posted',
                'description' => 'Shared feedback on your recent course',
            ],
        ];

        return array_slice($achievements, 0, rand(0, 2));
    }

    /**
     * Get personalized recommendations
     */
    private function getPersonalizedRecommendations(User $student): array
    {
        // Mock recommendations - replace with AI/ML recommendation engine
        $recommendations = [
            [
                'icon' => '🚀',
                'title' => 'Advanced React Course',
                'description' => 'Based on your progress in JavaScript, this course will take your skills to the next level.',
                'link' => route('web.courses'),
            ],
            [
                'icon' => '👥',
                'title' => 'Join Study Group',
                'description' => 'Connect with other learners in our community forum to discuss concepts and solve problems together.',
                'link' => '#', // Replace with forum link
            ],
            [
                'icon' => '🎯',
                'title' => 'Set Learning Goals',
                'description' => 'Set specific, achievable goals for next week to maintain your momentum.',
                'link' => route('student.dashboard'),
            ],
        ];

        return array_slice($recommendations, 0, rand(1, 3));
    }

    /**
     * Check if student has meaningful activity to warrant a report
     */
    private function hasMeaningfulActivity(array $reportData): bool
    {
        return (
            $reportData['courses_enrolled'] > 0 ||
            $reportData['lessons_completed'] > 0 ||
            count($reportData['upcoming_sessions'] ?? []) > 0 ||
            count($reportData['achievements'] ?? []) > 0
        );
    }
}
