<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\UploadCourseVideosToVimeoJob;
use App\Mail\CourseApprovalMail;
use App\Mail\CourseRejectionMail;
use App\Models\Course;
use App\Models\CoursePurchase;
use App\Models\Lesson;
use App\Models\OrderItem;
use App\Models\UserPurchasedItem;
use App\Services\ActivityNotificationService;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    public function show($id)
    {
        $course = $this->loadCourse($id, withContentCounts: true);
        $stats = $course->purchaseStats();

        return view('admin.showCourse', compact('course', 'stats'));
    }

    public function purchases($id)
    {
        $course = $this->loadCourse($id);
        $stats = $course->purchaseStats();
        $courseTypes = Course::modelTypeValues();

        $fromItems = UserPurchasedItem::with('user')
            ->whereIn('purchasable_type', $courseTypes)
            ->where('purchasable_id', $course->id)
            ->latest()
            ->get()
            ->map(function ($item) use ($course) {
                return [
                    'student_id' => $item->user_id,
                    'name' => $item->user?->full_name ?? 'Unknown',
                    'email' => $item->user?->email ?? 'N/A',
                    'source' => 'Checkout',
                    'status' => $item->active ? 'Active' : 'Inactive',
                    'payment_status' => 'Enrolled',
                    'purchased_at' => $item->created_at,
                    'amount' => $course->price,
                ];
            });

        $fromLegacy = CoursePurchase::with('student')
            ->where('course_id', $course->id)
            ->latest()
            ->get()
            ->map(function ($purchase) use ($course) {
                return [
                    'student_id' => $purchase->student_id,
                    'name' => $purchase->student?->full_name ?? 'Unknown',
                    'email' => $purchase->student?->email ?? 'N/A',
                    'source' => 'Legacy enrollment',
                    'status' => $purchase->is_active ? 'Active' : 'Inactive',
                    'payment_status' => ucfirst($purchase->payment_status ?? 'pending'),
                    'purchased_at' => $purchase->created_at,
                    'amount' => $course->price,
                ];
            });

        $purchases = $fromItems
            ->concat($fromLegacy)
            ->sortByDesc('purchased_at')
            ->unique('student_id')
            ->values();

        return view('admin.courses.purchases', compact('course', 'stats', 'purchases'));
    }

    public function revenue($id)
    {
        $course = $this->loadCourse($id);
        $stats = $course->purchaseStats();
        $courseTypes = Course::modelTypeValues();

        $transactions = OrderItem::with(['order.user'])
            ->where('item_id', $course->id)
            ->whereIn('model', $courseTypes)
            ->whereHas('order', function ($query) {
                $query->whereIn('status', ['completed', 'paid'])
                    ->where('is_active', true);
            })
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'order_id' => $item->order_id,
                    'transaction_id' => $item->order?->transaction_id ?? 'N/A',
                    'student' => $item->order?->user?->full_name ?? 'Unknown',
                    'student_email' => $item->order?->user?->email ?? 'N/A',
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'tax' => $item->tax,
                    'total' => $item->total,
                    'status' => ucfirst($item->order?->status ?? 'unknown'),
                    'payment_method' => $item->order?->payment_method ?? 'N/A',
                    'purchased_at' => $item->order?->created_at,
                ];
            });

        return view('admin.courses.revenue', compact('course', 'stats', 'transactions'));
    }

    public function reviews($id)
    {
        $course = $this->loadCourse($id);
        $stats = $course->purchaseStats();

        $reviews = $course->reviews()
            ->with('student')
            ->latest()
            ->get();

        return view('admin.courses.reviews', compact('course', 'stats', 'reviews'));
    }

    public function content($id)
    {
        $course = Course::with([
            'educator',
            'category',
            'sections' => fn ($q) => $q->orderBy('order')->orderBy('id'),
            'sections.lessons.courseSection',
        ])->findOrFail($id);

        $stats = $course->purchaseStats();

        return view('admin.courses.content', compact('course', 'stats'));
    }

    public function approve(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $oldStatus = $course->approval_status;

        $course->update([
            'approval_status' => 'approved',
            'review_note' => null
        ]);

        Lesson::where('course_id', $course->id)->update(['active' => true]);

        try {
            EmailService::send(
                $course->educator->email,
                new CourseApprovalMail($course),
                'emails'
            );
        } catch (\Exception $e) {
            Log::error('Failed to send course approval email: ' . $e->getMessage());
        }

        return back()->with('success', 'Course approved successfully. Video upload to Vimeo is scheduled to run in a minute.');
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

        try {
            EmailService::send(
                $course->educator->email,
                new CourseRejectionMail($course, $request->review_note),
                'emails'
            );
        } catch (\Exception $e) {
            Log::error('Failed to send course rejection email: ' . $e->getMessage());
        }

        return back()->with('success', 'Course rejected with note');
    }

    private function loadCourse($id, bool $withContentCounts = false)
    {
        $relations = ['educator', 'category', 'features', 'reviews'];

        if ($withContentCounts) {
            $relations['sections'] = fn ($q) => $q->orderBy('order')->orderBy('id');
            $relations[] = 'sections.lessons';
        }

        return Course::with($relations)->findOrFail($id);
    }
}
