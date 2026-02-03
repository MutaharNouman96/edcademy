<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Services\EmailService;
use App\Services\ActivityNotificationService;
use App\Mail\CourseApprovalMail;
use App\Mail\CourseRejectionMail;

class CourseController extends Controller
{
    public function approve(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $oldStatus = $course->approval_status;

        $course->update([
            'approval_status' => 'approved',
            'review_note' => null // Clear any previous rejection note
        ]);

        // Log activity
        ActivityNotificationService::logAndNotify(
            auth()->user(),
            'approve',
            'Course',
            $course->id,
            $course->title,
            ['approval_status' => $oldStatus],
            ['approval_status' => 'approved'],
            "Approved course '{$course->title}' by {$course->user->full_name}",
            ['educator_id' => $course->user_id, 'course_subject' => $course->subject]
        );

        // Send course approval email to educator
        try {
            EmailService::send(
                $course->user->email,
                new CourseApprovalMail($course),
                'emails'
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send course approval email: ' . $e->getMessage());
        }

        return back()->with('success', 'Course approved successfully');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'review_note' => 'required|string|max:1000'
        ]);

        $course = Course::findOrFail($id);
        $oldStatus = $course->approval_status;

        $course->update([
            'approval_status' => 'rejected',
            'review_note' => $request->review_note
        ]);

        // Log activity
        ActivityNotificationService::logAndNotify(
            auth()->user(),
            'reject',
            'Course',
            $course->id,
            $course->title,
            ['approval_status' => $oldStatus],
            ['approval_status' => 'rejected', 'review_note' => $request->review_note],
            "Rejected course '{$course->title}' by {$course->user->full_name}",
            ['educator_id' => $course->user_id, 'review_note' => $request->review_note]
        );

        // Send course rejection email to educator
        try {
            EmailService::send(
                $course->user->email,
                new CourseRejectionMail($course, $request->review_note),
                'emails'
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send course rejection email: ' . $e->getMessage());
        }

        return back()->with('success', 'Course rejected with note');
    }
}