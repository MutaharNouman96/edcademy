<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Course;
use App\Services\ActivityNotificationService;

class TestActivityNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:activity-notifications {user?} {action?} {entity?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test activity notifications for different user actions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userEmail = $this->argument('user');
        $action = $this->argument('action') ?: 'create_course';
        $entity = $this->argument('entity') ?: 'Course';

        if ($userEmail) {
            $user = User::where('email', $userEmail)->first();
            if (!$user) {
                $this->error("User with email {$userEmail} not found.");
                return Command::FAILURE;
            }
        } else {
            // Get first available user for testing
            $user = User::first();
            if (!$user) {
                $this->error("No users found in database.");
                return Command::FAILURE;
            }
            $this->info("Using user: {$user->email}");
        }

        $this->info("Testing activity notification: {$action} on {$entity}");
        $this->info("User: {$user->full_name} ({$user->email})");

        // Test different activity types
        switch ($action) {
            case 'create_course':
                $this->testCourseCreation($user);
                break;
            case 'login':
                $this->testLogin($user);
                break;
            case 'enroll_course':
                $this->testCourseEnrollment($user);
                break;
            case 'send_message':
                $this->testMessageSending($user);
                break;
            case 'approve_course':
                $this->testCourseApproval($user);
                break;
            case 'process_payout':
                $this->testPayoutProcessing($user);
                break;
            default:
                $this->error("Unknown action: {$action}");
                return Command::FAILURE;
        }

        $this->info("Activity notification test completed.");
        return Command::SUCCESS;
    }

    private function testCourseCreation(User $user)
    {
        // Find or create a test course
        $course = Course::first();
        if (!$course) {
            $this->warn("No courses found. Creating a test course...");
            $course = Course::create([
                'user_id' => $user->id,
                'title' => 'Test Course for Notifications',
                'description' => 'This is a test course created for activity notification testing.',
                'subject' => 'Testing',
                'price' => 99.99,
                'status' => 'draft',
                'approval_status' => 'pending'
            ]);
        }

        ActivityNotificationService::logAndNotify(
            $user,
            'create_course',
            'Course',
            $course->id,
            $course->title,
            null,
            [
                'title' => $course->title,
                'subject' => $course->subject,
                'price' => $course->price,
                'status' => $course->status
            ],
            "Created new course '{$course->title}' for testing",
            ['test_mode' => true, 'created_via' => 'test_command']
        );

        $this->info("✅ Logged course creation activity");
    }

    private function testLogin(User $user)
    {
        ActivityNotificationService::logAndNotify(
            $user,
            'login',
            'User',
            $user->id,
            $user->full_name,
            null,
            [
                'login_at' => now(),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Test Command',
                'role' => $user->role
            ],
            "Test login activity from command line",
            ['test_mode' => true, 'login_method' => 'test_command']
        );

        $this->info("✅ Logged login activity");
    }

    private function testCourseEnrollment(User $user)
    {
        $course = Course::first();
        if (!$course) {
            $this->warn("No courses available for enrollment test");
            return;
        }

        ActivityNotificationService::logAndNotify(
            $user,
            'enroll_course',
            'Course',
            $course->id,
            $course->title,
            null,
            [
                'enrolled_at' => now(),
                'price_paid' => $course->price,
                'educator_id' => $course->user_id
            ],
            "Test enrollment in course '{$course->title}'",
            ['test_mode' => true, 'payment_method' => 'test_enrollment']
        );

        $this->info("✅ Logged course enrollment activity");
    }

    private function testMessageSending(User $user)
    {
        // Find another user to "send message to"
        $recipient = User::where('id', '!=', $user->id)->first();
        if (!$recipient) {
            $this->warn("No other users available for message test");
            return;
        }

        ActivityNotificationService::logAndNotify(
            $user,
            'send_message',
            'ChatMessage',
            rand(1000, 9999), // Fake message ID
            "Message to {$recipient->full_name}",
            null,
            [
                'message' => 'This is a test message from the activity notification system.',
                'type' => 'text',
                'receiver_id' => $recipient->id,
                'chat_id' => rand(100, 999)
            ],
            "Sent test message to {$recipient->full_name}",
            ['test_mode' => true, 'message_type' => 'text']
        );

        $this->info("✅ Logged message sending activity");
    }

    private function testCourseApproval(User $user)
    {
        $course = Course::where('approval_status', 'pending')->first();
        if (!$course) {
            $this->warn("No pending courses available for approval test");
            return;
        }

        ActivityNotificationService::logAndNotify(
            $user,
            'approve',
            'Course',
            $course->id,
            $course->title,
            ['approval_status' => 'pending'],
            ['approval_status' => 'approved'],
            "Approved course '{$course->title}' in test mode",
            ['test_mode' => true, 'approved_by' => $user->full_name]
        );

        $this->info("✅ Logged course approval activity");
    }

    private function testPayoutProcessing(User $user)
    {
        // Find an educator user
        $educator = User::where('role', 'educator')->first();
        if (!$educator) {
            $this->warn("No educators available for payout test");
            return;
        }

        ActivityNotificationService::logAndNotify(
            $user,
            'process_payout',
            'Payout',
            rand(1000, 9999), // Fake payout ID
            "Payout to {$educator->full_name}",
            ['status' => 'pending'],
            ['status' => 'completed', 'processed_by' => $user->full_name],
            "Processed test payout of $150.00 to {$educator->full_name}",
            ['test_mode' => true, 'amount' => 150.00, 'currency' => 'USD']
        );

        $this->info("✅ Logged payout processing activity");
    }
}
