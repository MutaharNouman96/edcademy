<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonVideoView;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisualReportsController extends Controller
{
    public function index(Request $request)
    {
        $end = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        $start = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->subDays(30)->startOfDay();

        if ($start->greaterThan($end)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }

        $labels = [];
        foreach (CarbonPeriod::create($start->copy()->startOfDay(), '1 day', $end->copy()->startOfDay()) as $day) {
            $labels[] = $day->format('Y-m-d');
        }

        // 1) Courses added over time
        $coursesAddedByDate = Course::query()
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->pluck('c', 'd')
            ->toArray();

        $coursesAddedSeries = array_map(
            fn ($d) => (int) ($coursesAddedByDate[$d] ?? 0),
            $labels
        );

        // 2) Courses sold vs revenue earned (from completed, active orders)
        $salesRows = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed')
            ->where('orders.is_active', true)
            ->where('order_items.model', Course::class)
            ->whereBetween('orders.created_at', [$start, $end])
            ->selectRaw('DATE(orders.created_at) as d, COALESCE(SUM(order_items.quantity),0) as sold, COALESCE(SUM(order_items.total),0) as revenue')
            ->groupBy('d')
            ->get();

        $soldByDate = [];
        $revenueByDate = [];
        foreach ($salesRows as $row) {
            $soldByDate[$row->d] = (int) $row->sold;
            $revenueByDate[$row->d] = (float) $row->revenue;
        }

        $coursesSoldSeries = array_map(fn ($d) => (int) ($soldByDate[$d] ?? 0), $labels);
        $revenueSeries = array_map(fn ($d) => (float) ($revenueByDate[$d] ?? 0), $labels);

        // 3) New educators signed up vs new students joined
        $educatorsByDate = User::query()
            ->where('role', 'educator')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->pluck('c', 'd')
            ->toArray();

        $studentsByDate = User::query()
            ->where('role', 'student')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->pluck('c', 'd')
            ->toArray();

        $newEducatorsSeries = array_map(fn ($d) => (int) ($educatorsByDate[$d] ?? 0), $labels);
        $newStudentsSeries = array_map(fn ($d) => (int) ($studentsByDate[$d] ?? 0), $labels);

        // 4) New videos and their watch time
        $newVideosByDate = Lesson::query()
            ->where('type', 'video')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->pluck('c', 'd')
            ->toArray();

        $watchTimeByDate = LessonVideoView::query()
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as d, COALESCE(SUM(watch_time),0) as s')
            ->groupBy('d')
            ->pluck('s', 'd')
            ->toArray();

        $newVideosSeries = array_map(fn ($d) => (int) ($newVideosByDate[$d] ?? 0), $labels);
        // Convert seconds -> minutes (float) for chart readability.
        $watchTimeMinutesSeries = array_map(fn ($d) => round(((int) ($watchTimeByDate[$d] ?? 0)) / 60, 2), $labels);

        $kpis = [
            'courses_added' => array_sum($coursesAddedSeries),
            'courses_sold' => array_sum($coursesSoldSeries),
            'revenue' => array_sum($revenueSeries),
            'new_educators' => array_sum($newEducatorsSeries),
            'new_students' => array_sum($newStudentsSeries),
            'new_videos' => array_sum($newVideosSeries),
            'watch_time_minutes' => array_sum($watchTimeMinutesSeries),
        ];

        return view('admin.visual_reports.index', [
            'start' => $start,
            'end' => $end,
            'labels' => $labels,
            'coursesAddedSeries' => $coursesAddedSeries,
            'coursesSoldSeries' => $coursesSoldSeries,
            'revenueSeries' => $revenueSeries,
            'newEducatorsSeries' => $newEducatorsSeries,
            'newStudentsSeries' => $newStudentsSeries,
            'newVideosSeries' => $newVideosSeries,
            'watchTimeMinutesSeries' => $watchTimeMinutesSeries,
            'kpis' => $kpis,
        ]);
    }
}

