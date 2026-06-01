<?php

namespace App\Services;

use App\Mail\AdminDigestCategoryMail;
use App\Models\AdminEmailLog;
use App\Models\Course;
use App\Models\EducatorProfile;
use App\Models\Lesson;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\CarbonInterface;

class AdminNotificationsService
{
    /**
     * Send all admin digest emails for the last 12 hours.
     */
    public function sendDigest(): void
    {
        $to = now();
        $from = now()->subHours(12);
        $recipient = (string) env('ADMIN_EMAIL');

        $this->sendPendingEducatorsDigest($recipient, $from, $to);
        $this->sendPendingCoursesDigest($recipient, $from, $to);
        $this->sendNewLessonsDigest($recipient, $from, $to);
        $this->sendNewStudentsDigest($recipient, $from, $to);
        $this->sendPurchasesDigest($recipient, $from, $to);
    }

    private function sendPendingEducatorsDigest(string $recipient, CarbonInterface $from, CarbonInterface $to): void
    {
        $rows = EducatorProfile::query()
            ->with('user:id,first_name,last_name,email,created_at')
            ->where('status', 'pending')
            ->whereBetween('created_at', [$from, $to])
            ->orderByDesc('created_at')
            ->get()
            ->map(function (EducatorProfile $profile): string {
                $user = $profile->user;
                $name = $user ? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) : 'Unknown Educator';
                $email = $user->email ?? 'N/A';
                $createdAt = optional($profile->created_at)->format('M j, Y g:i A') ?? 'N/A';

                return "{$name} ({$email}) - pending since {$createdAt}";
            });

        $title = 'Pending Educator Signups';
        $this->sendCategoryDigest('pending_educators', $title, $rows, $recipient, $from, $to);
    }

    private function sendPendingCoursesDigest(string $recipient, CarbonInterface $from, CarbonInterface $to): void
    {
        $rows = Course::query()
            ->with('educator:id,first_name,last_name,email')
            ->where('approval_status', 'pending')
            ->whereBetween('created_at', [$from, $to])
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Course $course): string {
                $educator = $course->educator;
                $educatorName = $educator ? trim(($educator->first_name ?? '') . ' ' . ($educator->last_name ?? '')) : 'Unknown Educator';
                $createdAt = optional($course->created_at)->format('M j, Y g:i A') ?? 'N/A';

                return "{$course->title} by {$educatorName} - submitted {$createdAt}";
            });

        $title = 'Pending Course Submissions';
        $this->sendCategoryDigest('pending_courses', $title, $rows, $recipient, $from, $to);
    }

    private function sendNewLessonsDigest(string $recipient, CarbonInterface $from, CarbonInterface $to): void
    {
        $rows = Lesson::query()
            ->with('course:id,title,user_id')
            ->whereBetween('created_at', [$from, $to])
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Lesson $lesson): string {
                $courseTitle = optional($lesson->course)->title ?? 'Unknown Course';
                $createdAt = optional($lesson->created_at)->format('M j, Y g:i A') ?? 'N/A';
                $state = $lesson->active ? 'active' : 'pending approval';

                return "{$lesson->title} (Course: {$courseTitle}) - {$state}, added {$createdAt}";
            });

        $title = 'Newly Added Lessons';
        $this->sendCategoryDigest('new_lessons', $title, $rows, $recipient, $from, $to);
    }

    private function sendNewStudentsDigest(string $recipient, CarbonInterface $from, CarbonInterface $to): void
    {
        $rows = User::query()
            ->where('role', 'student')
            ->whereBetween('created_at', [$from, $to])
            ->orderByDesc('created_at')
            ->get()
            ->map(function (User $student): string {
                $name = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
                $createdAt = optional($student->created_at)->format('M j, Y g:i A') ?? 'N/A';

                return "{$name} ({$student->email}) - joined {$createdAt}";
            });

        $title = 'New Student Signups';
        $this->sendCategoryDigest('new_students', $title, $rows, $recipient, $from, $to);
    }

    private function sendPurchasesDigest(string $recipient, CarbonInterface $from, CarbonInterface $to): void
    {
        $courseModelValues = Course::modelTypeValues();

        $rows = OrderItem::query()
            ->with(['order.user:id,first_name,last_name,email', 'item'])
            ->whereIn('model', $courseModelValues)
            ->whereHas('order', function ($query) {
                $query->whereIn('status', ['paid', 'completed']);
            })
            ->whereBetween('created_at', [$from, $to])
            ->orderByDesc('created_at')
            ->get()
            ->map(function (OrderItem $orderItem): string {
                $buyer = optional($orderItem->order)->user;
                $buyerName = $buyer ? trim(($buyer->first_name ?? '') . ' ' . ($buyer->last_name ?? '')) : 'Unknown Student';
                $buyerEmail = $buyer->email ?? 'N/A';
                $itemTitle = optional($orderItem->item)->title ?? "Course #{$orderItem->item_id}";
                $createdAt = optional($orderItem->created_at)->format('M j, Y g:i A') ?? 'N/A';
                $total = number_format((float) $orderItem->total, 2);

                return "{$itemTitle} purchased by {$buyerName} ({$buyerEmail}) - \${$total} at {$createdAt}";
            });

        $title = 'New Course Purchases';
        $this->sendCategoryDigest('course_purchases', $title, $rows, $recipient, $from, $to);
    }

    private function sendCategoryDigest(
        string $category,
        string $title,
        $rows,
        string $recipient,
        CarbonInterface $from,
        CarbonInterface $to
    ): void {
        $subject = sprintf('%s Digest (Last 12 Hours) - %d', $title, $rows->count());
        $meta = [
            'from' => $from->toDateTimeString(),
            'to' => $to->toDateTimeString(),
            'count' => $rows->count(),
        ];

        if (empty($recipient)) {
            $this->logResult($category, 'failed', null, $subject, $meta, 'ADMIN_EMAIL is not configured.');
            return;
        }

        try {
            EmailService::send(
                $recipient,
                new AdminDigestCategoryMail($title, $rows, $from, $to),
                'emails'
            );

            $this->logResult($category, 'success', $recipient, $subject, $meta);
        } catch (\Throwable $e) {
            $this->logResult($category, 'failed', $recipient, $subject, $meta, $e->getMessage());
        }
    }

    private function logResult(
        string $category,
        string $status,
        ?string $recipientEmail,
        ?string $subject,
        array $meta = [],
        ?string $errorMessage = null
    ): void {
        try {
            AdminEmailLog::create([
                'category' => $category,
                'status' => $status,
                'recipient_email' => $recipientEmail,
                'subject' => $subject,
                'meta' => $meta,
                'error_message' => $errorMessage,
                'sent_at' => $status === 'success' ? now() : null,
            ]);
        } catch (\Throwable $e) {
            // Never throw from logging to keep digest execution safe.
        }
    }
}
