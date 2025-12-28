<?php

namespace App\Http\Controllers\Educator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\UserPurchasedItem;
use App\Models\VideoStat;
use App\Models\Earning;
use App\Models\Lesson;
use App\Models\LessonVideoView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $educatorId = Auth::id();

        /**
         * ======================
         * COURSES
         * ======================
         */


        $totalCourses = Course::where('user_id', $educatorId)->count();

        $draftCourses = Course::where('user_id', $educatorId)
            ->where('status', 'Draft')
            ->count();

        $publishedCourses = Course::where('user_id', $educatorId)
            ->where('status', 'Published')
            ->count();

        /**
         * ======================
         * STUDENTS
         * ======================
         * Count distinct users who purchased:
         *  - educator courses
         *  - educator lessons
         */
        $courseIds = Course::where('user_id', $educatorId)->pluck('id');

        $lessonIds = Lesson::whereIn('course_id', $courseIds)->pluck('id');

        $totalStudents = UserPurchasedItem::where(function ($q) use ($courseIds, $lessonIds) {
            $q->where(function ($q) use ($courseIds) {
                $q->where('purchasable_type', Course::class)
                    ->whereIn('purchasable_id', $courseIds);
            })
                ->orWhere(function ($q) use ($lessonIds) {
                    $q->where('purchasable_type', Lesson::class)
                        ->whereIn('purchasable_id', $lessonIds);
                });
        })
            ->distinct('user_id')
            ->count('user_id');

        /**
         * New students this week
         */
        $newStudentsThisWeek = UserPurchasedItem::where(function ($q) use ($courseIds, $lessonIds) {
            $q->where(function ($q) use ($courseIds) {
                $q->where('purchasable_type', Course::class)
                    ->whereIn('purchasable_id', $courseIds);
            })
                ->orWhere(function ($q) use ($lessonIds) {
                    $q->where('purchasable_type', Lesson::class)
                        ->whereIn('purchasable_id', $lessonIds);
                });
        })
            ->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->distinct('user_id')
            ->count('user_id');

        /**
         * ======================
         * VIDEO STATS (7 DAYS)
         * ======================
         */
        $_30DaysAgo = Carbon::now()->subDays(30);

        $videoStats = LessonVideoView::whereIn('lesson_id', $lessonIds)
            ->where('created_at', '>=', $_30DaysAgo)
            ->selectRaw('
                COUNT(id) as total_views,
                AVG(watch_time) as avg_watch_time
            ')
            ->first();

        $totalViews7Days = (int) ($videoStats->total_views ?? 0);

        $avgWatchSeconds = (int) ($videoStats->avg_watch_time ?? 0);

        $avgWatchFormatted = gmdate(
            floor($avgWatchSeconds / 3600) > 0 ? 'H:i:s' : 'i:s',
            $avgWatchSeconds
        );

        /**
         * ======================
         * EARNINGS
         * ======================
         */
        $escrowBalance = Earning::where('educator_id', $educatorId)
            ->where('status', 'pending')
            ->sum('net_amount');

        $earnedTotal = Earning::where('educator_id', $educatorId)
            ->where('status', 'paid')
            ->sum('net_amount');


        $latestCourses = Course::where('user_id', $educatorId)->active()->published()->orderByDesc("publish_date")->with("educator", "category", "reviews", "lessons")->limit(4)->get();
        $latestVideos = Lesson::where('type', 'video')->whereIn('course_id', $courseIds)->published()->with("course", "lesson_video_views", "lesson_video_comments")->limit(4)->get();

        


        return view('educator.dashboard', compact(
            'totalCourses',
            'draftCourses',
            'publishedCourses',
            'totalStudents',
            'newStudentsThisWeek',
            'totalViews7Days',
            'avgWatchFormatted',
            'escrowBalance',
            'earnedTotal',
            'latestCourses',
            'latestVideos'
        ));
    }

    public function profile()
    {
        $educator = auth()->user()->educatorProfile;
        return view('educator.profile', compact('educator'));
    }


    public function profile_update(Request $request)
    {
        $user = auth()->user();
        $educator = $user->educatorProfile;

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'primary_subject' => 'required|string|max:255',
            'hourly_rate' => 'required|numeric|min:5',
            'teaching_levels' => 'nullable|array',
            'certifications' => 'nullable|string',
            'preferred_teaching_style' => 'nullable|string|max:255',
            'govt_id' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'degree_proof' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'intro_video' => 'nullable|file|mimetypes:video/mp4,video/mov|max:51200',
        ]);

        // Update user info
        $user->update(['first_name' => $request->first_name, 'last_name' => $request->last_name, 'email' => $request->email]);

        // Move uploaded files to public/storage manually
        $paths = [];
        if ($request->hasFile('govt_id')) {
            $file = $request->file('govt_id');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/educators/ids'), $filename);
            $paths['govt_id_path'] = 'educators/ids/' . $filename;
        }

        if ($request->hasFile('degree_proof')) {
            $file = $request->file('degree_proof');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/educators/degrees'), $filename);
            $paths['degree_proof_path'] = 'educators/degrees/' . $filename;
        }

        if ($request->hasFile('intro_video')) {
            $file = $request->file('intro_video');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/educators/videos'), $filename);
            $paths['intro_video_path'] = 'educators/videos/' . $filename;
        }

        $educator->update(array_merge([
            'primary_subject' => $request->primary_subject,
            'hourly_rate' => $request->hourly_rate,
            'teaching_levels' => $request->teaching_levels,
            'certifications' => $request->certifications,
            'preferred_teaching_style' => $request->preferred_teaching_style,
        ], $paths));

        return back()->with('success', 'Profile updated successfully!');
    }
}
