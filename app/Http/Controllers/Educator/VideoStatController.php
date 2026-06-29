<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonVideoView;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class VideoStatController extends Controller
{
    public function index(Request $request)
    {
        $educatorId = auth()->id();
        $courseId = $request->filled('course_id') ? (int) $request->course_id : null;
        $dateRange = $request->get('date_range');

        $myCourses = Course::query()
            ->where('user_id', $educatorId)
            ->orderBy('title')
            ->get(['id', 'title']);

        if ($courseId && !$myCourses->contains('id', $courseId)) {
            abort(403);
        }

        $courseIds = $courseId ? collect([$courseId]) : $myCourses->pluck('id');

        $lessonIds = Lesson::query()
            ->whereIn('course_id', $courseIds)
            ->where('type', 'video')
            ->pluck('id');

        $viewsQuery = LessonVideoView::query()->whereIn('lesson_id', $lessonIds);
        $viewsQuery = $this->applyDateRange($viewsQuery, $dateRange);

        $totalViews = (clone $viewsQuery)->count();
        $averageWatchTimeSeconds = (int) (clone $viewsQuery)->avg('watch_time');
        $averageWatchTime = format_seconds($averageWatchTimeSeconds);

        $completedCount = (clone $viewsQuery)->where('completed', '>', 0)->count();
        $completionRate = $totalViews > 0 ? (int) round($completedCount / $totalViews * 100) : 0;

        $topLessonId = (clone $viewsQuery)
            ->selectRaw('lesson_id, COUNT(*) as view_count')
            ->groupBy('lesson_id')
            ->orderByDesc('view_count')
            ->value('lesson_id');

        $topPerformingVideo = $topLessonId
            ? (Lesson::find($topLessonId)?->title ?? '—')
            : '—';

        $aggregates = (clone $viewsQuery)
            ->selectRaw('
                lesson_id,
                COUNT(*) as views,
                AVG(watch_time) as avg_watch_time,
                SUM(CASE WHEN completed > 0 THEN 1 ELSE 0 END) as completed_views,
                SUM(CASE WHEN liked IN (1, "1") THEN 1 ELSE 0 END) as likes
            ')
            ->groupBy('lesson_id')
            ->get()
            ->keyBy('lesson_id');

        $lessons = Lesson::query()
            ->whereIn('course_id', $courseIds)
            ->where('type', 'video')
            ->with('course:id,title')
            ->withCount('lesson_video_comments')
            ->orderBy('title')
            ->get();

        $videoBreakdown = $lessons
            ->map(function (Lesson $lesson) use ($aggregates) {
                $stats = $aggregates->get($lesson->id);
                $views = (int) ($stats->views ?? 0);

                return [
                    'lesson' => $lesson,
                    'views' => $views,
                    'avg_watch_time' => format_seconds($stats->avg_watch_time ?? 0),
                    'avg_watch_time_seconds' => (int) ($stats->avg_watch_time ?? 0),
                    'completion_rate' => $views > 0
                        ? (int) round(($stats->completed_views ?? 0) / $views * 100)
                        : 0,
                    'likes' => (int) ($stats->likes ?? 0),
                    'comments' => (int) $lesson->lesson_video_comments_count,
                ];
            })
            ->sortByDesc('views')
            ->values();

        [$chartLabels, $chartData] = $this->buildViewsChart($lessonIds, $dateRange);

        return view('crm.educator.video_stats.index', compact(
            'myCourses',
            'totalViews',
            'averageWatchTime',
            'completionRate',
            'topPerformingVideo',
            'videoBreakdown',
            'chartLabels',
            'chartData',
            'courseId',
            'dateRange',
        ));
    }

    private function applyDateRange(Builder $query, ?string $dateRange): Builder
    {
        return match ($dateRange) {
            'last_7_days' => $query->where('created_at', '>=', Carbon::now()->subDays(7)),
            'last_30_days' => $query->where('created_at', '>=', Carbon::now()->subDays(30)),
            default => $query,
        };
    }

    /**
     * @return array{0: array<int, string>, 1: array<int, int>}
     */
    private function buildViewsChart($lessonIds, ?string $dateRange): array
    {
        $days = match ($dateRange) {
            'last_7_days' => 7,
            'last_30_days' => 30,
            default => 30,
        };

        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M j');
            $data[] = LessonVideoView::query()
                ->whereIn('lesson_id', $lessonIds)
                ->whereDate('created_at', $date->toDateString())
                ->count();
        }

        return [$labels, $data];
    }
}
