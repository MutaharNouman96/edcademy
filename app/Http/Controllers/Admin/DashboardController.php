<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use App\Models\RefundRequest;
use App\Models\CourseReview;
use App\Models\Order;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\EmailService;
use App\Services\ActivityNotificationService;
use App\Mail\EducatorApprovalMail;
use App\Mail\EducatorRejectionMail;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Handle time range filtering
        $timeRange = $request->get('time_range', 'this_month');
        $dateRange = $this->getDateRange($timeRange);

        // Handle segment filtering
        $segment = $request->get('segment', 'all_users');

        // Handle region filtering (placeholder for now)
        $region = $request->get('region', 'all');

        // Get current period data
        $currentStart = $dateRange['current']['start'];
        $currentEnd = $dateRange['current']['end'];

        // Get previous period data for comparison
        $previousStart = $dateRange['previous']['start'];
        $previousEnd = $dateRange['previous']['end'];

        // Total Users (filtered by segment and region)
        $userQuery = $this->getUserQuery($segment, $region);
        $totalUsers = $userQuery->count();

        $newUsersCurrentPeriod = (clone $userQuery)->whereBetween('created_at', [$currentStart, $currentEnd])->count();
        $newUsersPreviousPeriod = (clone $userQuery)->whereBetween('created_at', [$previousStart, $previousEnd])->count();
        $usersGrowth = $newUsersPreviousPeriod > 0 ? (($newUsersCurrentPeriod - $newUsersPreviousPeriod) / $newUsersPreviousPeriod) * 100 : 0;

        // Active Educators - only show if segment is educators-related
        if (in_array($segment, ['all_users', 'educators', 'premium_educators'])) {
            $educatorQuery = User::where('role', 'educator');
            if ($segment === 'premium_educators') {
                $educatorQuery->whereHas('educatorProfile', function($q) {
                    $q->where('status', 'approved');
                });
            }

            $activeEducators = $educatorQuery->count();
            $newEducatorsCurrentPeriod = (clone $educatorQuery)->whereBetween('created_at', [$currentStart, $currentEnd])->count();
            $newEducatorsPreviousPeriod = (clone $educatorQuery)->whereBetween('created_at', [$previousStart, $previousEnd])->count();
        } else {
            // For students segment, don't show educator metrics
            $activeEducators = 0;
            $newEducatorsCurrentPeriod = 0;
            $newEducatorsPreviousPeriod = 0;
        }
        $educatorsGrowth = $newEducatorsPreviousPeriod > 0 ? (($newEducatorsCurrentPeriod - $newEducatorsPreviousPeriod) / $newEducatorsPreviousPeriod) * 100 : 0;

        // Revenue (completed orders) - filtered by date range, segment and region
        $orderQuery = Order::where('status', 'completed')->where('is_active', true);

        // If filtering by students, only include orders from students (with region filtering)
        if ($segment === 'students') {
            $studentIds = $this->getUserQuery('students', $region)->pluck('id');
            $orderQuery->whereIn('user_id', $studentIds);
        }

        $totalRevenue = $orderQuery->get()->sum(function ($order) {
            return $order->getTotalAttribute();
        });

        $revenueCurrentPeriod = (clone $orderQuery)->whereBetween('created_at', [$currentStart, $currentEnd])
                                                   ->get()
                                                   ->sum(function ($order) {
                                                       return $order->getTotalAttribute();
                                                   });

        $revenuePreviousPeriod = (clone $orderQuery)->whereBetween('created_at', [$previousStart, $previousEnd])
                                                    ->get()
                                                    ->sum(function ($order) {
                                                        return $order->getTotalAttribute();
                                                    });

        $revenueGrowth = $revenuePreviousPeriod > 0 ? (($revenueCurrentPeriod - $revenuePreviousPeriod) / $revenuePreviousPeriod) * 100 : 0;

        // Pending payouts (from completed payments with net_amount > 0)
        $pendingPayouts = Payment::where('status', 'completed')
                                ->where('net_amount', '>', 0)
                                ->sum('net_amount');

        // Disputes/Flagged Content (filtered by segment)
        $disputesCountQuery = RefundRequest::query();
        if ($segment === 'students') {
            $studentIds = $this->getUserQuery('students')->pluck('id');
            $disputesCountQuery->whereIn('student_id', $studentIds);
        }
        $totalDisputes = $disputesCountQuery->whereBetween('created_at', [$currentStart, $currentEnd])->count();

        $flaggedCountQuery = CourseReview::where('rating', '<=', 2);
        if ($segment === 'students') {
            $studentIds = $this->getUserQuery('students')->pluck('id');
            $flaggedCountQuery->whereIn('student_id', $studentIds);
        }
        $flaggedContent = $flaggedCountQuery->whereBetween('created_at', [$currentStart, $currentEnd])->count(); // Low rated reviews as flagged content

        // Premium educators (approved educators)
        $premiumEducators = User::where('role', 'educator')
                               ->whereHas('educatorProfile', function($query) {
                                   $query->where('status', 'approved');
                               })
                               ->count();

        // Live educators (those with active/live sessions this month)
        $liveEducators = \App\Models\SessionCall::whereIn('status', ['active', 'live', 'ongoing'])
                            ->whereBetween('created_at', [$currentStart, $currentEnd])
                            ->distinct('educator_id')
                            ->count('educator_id');

        // Recent payments (filtered by segment and region)
        $paymentsQuery = Payment::with(['student', 'educator']);
        if ($segment === 'students') {
            $studentIds = $this->getUserQuery('students', $region)->pluck('id');
            $paymentsQuery->whereIn('student_id', $studentIds);
        }
        $recentPayments = $paymentsQuery->latest()
                                        ->take(4)
                                        ->get()
                                        ->map(function($payment) {
                                            return [
                                                'id' => $payment->id,
                                                'transaction_id' => $payment->transaction_id ?? 'N/A',
                                                'student' => $payment->student ? $payment->student->full_name : 'Unknown',
                                                'educator' => $payment->educator ? $payment->educator->full_name : 'Unknown',
                                                'amount' => '$ ' . number_format($payment->gross_amount, 0),
                                                'status' => ucfirst($payment->status),
                                                'date' => $payment->created_at->format('M d, Y')
                                            ];
                                        });

        // New educators (recently registered educators)
        $newEducatorsList = User::where('role', 'educator')
                               ->with('educatorProfile')
                               ->latest()
                               ->take(4)
                               ->get()
                               ->map(function($educator) {
                                   return [
                                       'id' => $educator->id,
                                       'name' => $educator->full_name,
                                       'email' => $educator->email,
                                       'status' => $educator->educatorProfile ? ucfirst($educator->educatorProfile->status) : 'Pending',
                                       'joined' => $educator->created_at->format('M d, Y'),
                                       'courses_count' => $educator->courses ? $educator->courses->count() : 0
                                   ];
                               });

        return view('admin.dashboard', compact(
            'totalUsers',
            'newUsersCurrentPeriod',
            'usersGrowth',
            'activeEducators',
            'newEducatorsCurrentPeriod',
            'educatorsGrowth',
            'totalRevenue',
            'revenueCurrentPeriod',
            'revenueGrowth',
            'pendingPayouts',
            'totalDisputes',
            'flaggedContent',
            'premiumEducators',
            'liveEducators',
            'recentPayments',
            'newEducatorsList'
        ));
    }

    private function getDateRange($timeRange)
    {
        $now = Carbon::now();

        switch ($timeRange) {
            case 'last_7_days':
                return [
                    'current' => [
                        'start' => $now->copy()->subDays(7)->startOfDay(),
                        'end' => $now->endOfDay()
                    ],
                    'previous' => [
                        'start' => $now->copy()->subDays(14)->startOfDay(),
                        'end' => $now->copy()->subDays(7)->endOfDay()
                    ]
                ];

            case 'last_30_days':
                return [
                    'current' => [
                        'start' => $now->copy()->subDays(30)->startOfDay(),
                        'end' => $now->endOfDay()
                    ],
                    'previous' => [
                        'start' => $now->copy()->subDays(60)->startOfDay(),
                        'end' => $now->copy()->subDays(30)->endOfDay()
                    ]
                ];

            case 'quarter_to_date':
                $currentQuarterStart = $now->copy()->firstOfQuarter();
                $previousQuarterStart = $currentQuarterStart->copy()->subQuarter();
                $previousQuarterEnd = $currentQuarterStart->copy()->subDay();

                return [
                    'current' => [
                        'start' => $currentQuarterStart,
                        'end' => $now->endOfDay()
                    ],
                    'previous' => [
                        'start' => $previousQuarterStart,
                        'end' => $previousQuarterEnd
                    ]
                ];

            case 'year_to_date':
                $currentYearStart = $now->copy()->startOfYear();
                $previousYearStart = $currentYearStart->copy()->subYear();
                $previousYearEnd = $currentYearStart->copy()->subDay();

                return [
                    'current' => [
                        'start' => $currentYearStart,
                        'end' => $now->endOfDay()
                    ],
                    'previous' => [
                        'start' => $previousYearStart,
                        'end' => $previousYearEnd
                    ]
                ];

            case 'this_month':
            default:
                $currentMonthStart = $now->copy()->startOfMonth();
                $previousMonthStart = $currentMonthStart->copy()->subMonth();
                $previousMonthEnd = $currentMonthStart->copy()->subDay();

                return [
                    'current' => [
                        'start' => $currentMonthStart,
                        'end' => $now->endOfDay()
                    ],
                    'previous' => [
                        'start' => $previousMonthStart,
                        'end' => $previousMonthEnd
                    ]
                ];
        }
    }

    private function getUserQuery($segment = 'all_users', $region = 'all')
    {
        $query = User::query();

        // Apply segment filtering
        switch ($segment) {
            case 'students':
                $query->where('role', 'student');
                break;
            case 'educators':
                $query->where('role', 'educator');
                break;
            case 'premium_educators':
                $query->where('role', 'educator')
                      ->whereHas('educatorProfile', function($q) {
                          $q->where('status', 'approved');
                      });
                break;
            case 'all_users':
            default:
                // No additional filtering
                break;
        }

        // Apply region filtering
        if ($region !== 'all') {
            // Map region codes to location keywords
            $locationKeywords = [];
            switch ($region) {
                case 'uae':
                    $locationKeywords = ['uae', 'united arab emirates', 'dubai', 'abu dhabi', 'sharjah'];
                    break;
                case 'gcc':
                    $locationKeywords = ['saudi', 'kuwait', 'qatar', 'bahrain', 'oman', 'gcc'];
                    break;
                case 'eu':
                    $locationKeywords = ['europe', 'eu', 'uk', 'germany', 'france', 'italy', 'spain'];
                    break;
                case 'us':
                    $locationKeywords = ['usa', 'united states', 'america', 'us'];
                    break;
            }

            if (!empty($locationKeywords)) {
                $query->where(function($q) use ($locationKeywords) {
                    // Check student profiles for location
                    $q->whereHas('studentProfile', function($profileQuery) use ($locationKeywords) {
                        $profileQuery->where(function($locationQuery) use ($locationKeywords) {
                            foreach ($locationKeywords as $keyword) {
                                $locationQuery->orWhere('location', 'like', '%' . $keyword . '%');
                            }
                        });
                    });

                    // For educators, we could check educator profiles if they have location fields
                    // For now, just check student profiles since that's where we have location data
                });
            }
        }

        return $query;
    }


    public function manageEducators(Request $request)
    {
        $query = User::where('role', 'educator')->with('educatorProfile');

        if ($request->filled('status')) {
            $query->whereHas('educatorProfile', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%'])
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $educators = $query->latest()->paginate(10);

        return view('admin.manageEducators', compact('educators'));
    }

    public function updateEducatorStatus(Request $request, $id)
    {
        $educator = User::where('role','educator')->findOrFail($id);
        $oldStatus = $educator->educatorProfile->status;
        $newStatus = $request->status;

        $educator->educatorProfile()->update(['status' => $newStatus]);

        // Log activity
        ActivityNotificationService::logAndNotify(
            auth()->user(),
            $newStatus === 'approved' ? 'approve' : ($newStatus === 'rejected' ? 'reject' : 'update'),
            'Educator',
            $educator->id,
            "{$educator->full_name} educator application",
            ['status' => $oldStatus],
            ['status' => $newStatus, 'reason' => $request->reason],
            "Changed educator status from {$oldStatus} to {$newStatus} for {$educator->full_name}",
            ['educator_email' => $educator->email, 'reason' => $request->reason]
        );

        // Send email based on status change
        try {
            if ($newStatus === 'approved' && $oldStatus !== 'approved') {
                EmailService::send(
                    $educator->email,
                    new EducatorApprovalMail($educator),
                    'emails'
                );
            } elseif ($newStatus === 'rejected' && $oldStatus !== 'rejected') {
                EmailService::send(
                    $educator->email,
                    new EducatorRejectionMail($educator, $request->reason ?? ''),
                    'emails'
                );
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send educator status update email: ' . $e->getMessage());
        }

        return back()->with('success','Educator status updated');
    }

    public function manageStudents(Request $request)
    {
        $query = User::where('role', 'student')->with('studentProfile');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%'])
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $students = $query->latest()->paginate(10);

        return view('admin.manageStudents', compact('students'));
    }

    public function manageCourses(Request $request)
    {
        $query = Course::with('educator');

        if ($request->filled('status')) {
            if (in_array($request->status, ['published', 'draft'])) {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('educator', function ($educatorQuery) use ($search) {
                      $educatorQuery->where('first_name', 'like', '%' . $search . '%')
                                    ->orWhere('last_name', 'like', '%' . $search . '%')
                                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%']);
                  });
            });
        }

        $courses = $query->latest()->paginate(10);

        return view('admin.manageCourses', compact('courses'));
    }

    public function updateCourseStatus(Request $request, $id)
    {
        if (!in_array($request->status, ['published', 'draft'])) {
            return back()->with('error', 'Invalid status selected');
        }

        $course = Course::findOrFail($id);
        $course->update(['status' => $request->status]);
        return back()->with('success', 'Course status updated successfully');
    }

    public function deleteCourse($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return back()->with('success', 'Course deleted successfully');
    }

    public function showCourse($id)
    {
        $course = Course::with(['educator', 'category', 'sections.lessons'])->findOrFail($id);
        return view('admin.showCourse', compact('course'));
    }

    public function manageLessons(Request $request)
    {
        $query = Lesson::with(['course.educator', 'courseSection']);

        if ($request->filled('status')) {
            if (in_array($request->status, ['Draft', 'Published'])) {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('free')) {
            $query->where('free', $request->free === '1' ? 1 : 0);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('course', function ($courseQuery) use ($search) {
                      $courseQuery->where('title', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('course.educator', function ($educatorQuery) use ($search) {
                      $educatorQuery->where('first_name', 'like', '%' . $search . '%')
                                    ->orWhere('last_name', 'like', '%' . $search . '%')
                                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%']);
                  });
            });
        }

        $lessons = $query->latest()->paginate(15);

        // Get courses for filter dropdown
        $courses = Course::select('id', 'title')->get();

        return view('admin.manageLessons', compact('lessons', 'courses'));
    }

    public function updateLessonStatus(Request $request, $id)
    {
        if (!in_array($request->status, ['Draft', 'Published'])) {
            return back()->with('error', 'Invalid status selected');
        }

        $lesson = Lesson::findOrFail($id);
        $lesson->update(['status' => $request->status]);

        if ($request->status === 'Published' && !$lesson->published_at) {
            $lesson->update(['published_at' => now()]);
        }

        return back()->with('success', 'Lesson status updated successfully');
    }

    public function showLesson($id)
    {
        $lesson = Lesson::with(['course.educator', 'courseSection'])->findOrFail($id);

        return view('admin.showLesson', compact('lesson'));
    }
}
