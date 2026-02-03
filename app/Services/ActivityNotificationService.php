<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\User;
use App\Mail\ActivityNotificationMail;
use Illuminate\Support\Facades\Log;

class ActivityNotificationService
{
    /**
     * Log an activity and send notifications
     */
    public static function logAndNotify($user, string $actionType, string $entityType, $entityId = null, string $entityName = null, array $oldValues = null, array $newValues = null, string $description = null, array $metadata = null)
    {
        try {
            // Log the activity
            $activity = Activity::log($user, $actionType, $entityType, $entityId, $entityName, $oldValues, $newValues, $description, $metadata);

            if ($activity) {
                // Send notifications based on user role and action type
                self::sendNotifications($activity);
            }

            return $activity;
        } catch (\Exception $e) {
            Log::error('Failed to log activity: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Send notifications for an activity
     */
    private static function sendNotifications(Activity $activity)
    {
        $recipients = self::determineRecipients($activity);

        foreach ($recipients as $recipient) {
            try {
                EmailService::send(
                    $recipient->email,
                    new ActivityNotificationMail($activity, $recipient),
                    'emails'
                );
            } catch (\Exception $e) {
                Log::error("Failed to send activity notification to {$recipient->email}: " . $e->getMessage());
            }
        }
    }

    /**
     * Determine who should receive notifications for this activity
     */
    private static function determineRecipients(Activity $activity): array
    {
        $recipients = [];

        switch ($activity->user_role) {
            case 'admin':
                $recipients = self::getAdminActivityRecipients($activity);
                break;
            case 'educator':
                $recipients = self::getEducatorActivityRecipients($activity);
                break;
            case 'student':
                $recipients = self::getStudentActivityRecipients($activity);
                break;
        }

        return $recipients;
    }

    /**
     * Get recipients for admin activities
     */
    private static function getAdminActivityRecipients(Activity $activity): array
    {
        $recipients = [];

        // Notify other admins about admin actions
        $otherAdmins = User::where('role', 'admin')
            ->where('id', '!=', $activity->user_id)
            ->get();

        // For critical actions, notify key stakeholders
        if (self::isCriticalAdminAction($activity)) {
            // Could add educators, specific users, etc.
        }

        return $otherAdmins->toArray();
    }

    /**
     * Get recipients for educator activities
     */
    private static function getEducatorActivityRecipients(Activity $activity): array
    {
        $recipients = [];

        // Notify admins about educator actions
        $admins = User::where('role', 'admin')->get();

        // Notify related students for certain actions
        if (self::isStudentRelevantEducatorAction($activity)) {
            $students = self::getRelatedStudents($activity);
            $recipients = array_merge($recipients, $students->toArray());
        }

        return array_merge($admins->toArray(), $recipients);
    }

    /**
     * Get recipients for student activities
     */
    private static function getStudentActivityRecipients(Activity $activity): array
    {
        $recipients = [];

        // Notify admins about student activities
        $admins = User::where('role', 'admin')->get();

        // Notify related educators for certain actions
        if (self::isEducatorRelevantStudentAction($activity)) {
            $educators = self::getRelatedEducators($activity);
            $recipients = array_merge($recipients, $educators->toArray());
        }

        return array_merge($admins->toArray(), $recipients);
    }

    /**
     * Check if admin action is critical
     */
    private static function isCriticalAdminAction(Activity $activity): bool
    {
        return in_array($activity->action_type, [
            'delete', 'ban', 'suspend', 'approve', 'reject', 'process_payout'
        ]);
    }

    /**
     * Check if educator action is relevant to students
     */
    private static function isStudentRelevantEducatorAction(Activity $activity): bool
    {
        return in_array($activity->action_type, [
            'create_course', 'update_course', 'publish_course', 'schedule_session',
            'cancel_session', 'send_message', 'post_review'
        ]);
    }

    /**
     * Check if student action is relevant to educators
     */
    private static function isEducatorRelevantStudentAction(Activity $activity): bool
    {
        return in_array($activity->action_type, [
            'enroll_course', 'complete_lesson', 'send_message',
            'post_review', 'book_session', 'cancel_session'
        ]);
    }

    /**
     * Get students related to an activity
     */
    private static function getRelatedStudents(Activity $activity): \Illuminate\Database\Eloquent\Collection
    {
        // This would need to be implemented based on the specific relationships
        // For example, students enrolled in a course, students who booked sessions, etc.
        return collect(); // Placeholder
    }

    /**
     * Get educators related to an activity
     */
    private static function getRelatedEducators(Activity $activity): \Illuminate\Database\Eloquent\Collection
    {
        // This would need to be implemented based on the specific relationships
        // For example, course educators, session educators, etc.
        return collect(); // Placeholder
    }
}