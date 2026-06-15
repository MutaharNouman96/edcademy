<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CoursePurchase;
use App\Models\Lesson;
use App\Models\LessonVideoView;
use App\Models\ProgressTracking;
use App\Models\User;
use App\Models\UserPurchasedItem;
use Illuminate\Support\Collection;

class StudentCourseProgressService
{
    /**
     * Record that a student opened/viewed a lesson.
     */
    public function recordLessonView(User $student, Lesson $lesson, ?string $description = 'viewed'): ProgressTracking
    {
        return ProgressTracking::updateOrCreate(
            [
                'student_id' => $student->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'course_id' => $lesson->course_id,
                'description' => $description,
            ]
        );
    }

    /**
     * Record video watch time and optionally mark the lesson complete.
     */
    public function recordVideoWatch(
        User $student,
        Lesson $lesson,
        int $watchTimeSeconds,
        bool $completed = false
    ): array {
        $existing = LessonVideoView::query()
            ->where('user_id', $student->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        $watchTimeSeconds = max($existing?->watch_time ?? 0, $watchTimeSeconds);
        $completed = $completed || (bool) ($existing?->completed ?? false);

        $videoView = LessonVideoView::updateOrCreate(
            [
                'user_id' => $student->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'watch_time' => $watchTimeSeconds,
                'completed' => $completed,
            ]
        );

        $description = $completed ? 'completed' : 'viewed';
        $progress = $this->recordLessonView($student, $lesson, $description);

        return [
            'progress' => $progress,
            'video_view' => $videoView,
        ];
    }

    /**
     * Whether the student has purchased access to the lesson or its parent course.
     */
    public function studentHasLessonAccess(User $student, Lesson $lesson): bool
    {
        $hasUserPurchasedItem = UserPurchasedItem::query()
            ->where('user_id', $student->id)
            ->where('active', true)
            ->where(function ($q) use ($lesson) {
                $q->where(function ($q) use ($lesson) {
                    $q->where('purchasable_type', Lesson::class)
                        ->where('purchasable_id', $lesson->id);
                })->orWhere(function ($q) use ($lesson) {
                    $q->where('purchasable_type', Course::class)
                        ->where('purchasable_id', $lesson->course_id);
                });
            })
            ->exists();

        if ($hasUserPurchasedItem) {
            return true;
        }

        return CoursePurchase::query()
            ->where('student_id', $student->id)
            ->where('course_id', $lesson->course_id)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Lesson IDs the student has progress on for a given course.
     */
    public function getCompletedLessonIds(int $studentId, int $courseId): Collection
    {
        return ProgressTracking::query()
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->pluck('lesson_id');
    }

    public function isLessonCompleted(int $studentId, int $lessonId): bool
    {
        return ProgressTracking::query()
            ->where('student_id', $studentId)
            ->where('lesson_id', $lessonId)
            ->exists();
    }

    /**
     * Completed lesson counts keyed by course_id.
     *
     * @param  int[]  $courseIds
     */
    public function getCompletedLessonsCountByCourse(int $studentId, array $courseIds): Collection
    {
        if (empty($courseIds)) {
            return collect();
        }

        return ProgressTracking::query()
            ->where('student_id', $studentId)
            ->whereIn('course_id', $courseIds)
            ->selectRaw('course_id, COUNT(DISTINCT lesson_id) as completed_lessons')
            ->groupBy('course_id')
            ->pluck('completed_lessons', 'course_id');
    }

    /**
     * Course completion percentage (0–100).
     */
    public function getCourseProgressPercentage(int $studentId, int $courseId, int $totalLessons): float
    {
        if ($totalLessons <= 0) {
            return 0;
        }

        $completed = $this->getCompletedLessonIds($studentId, $courseId)->count();

        return round(min(100, ($completed / $totalLessons) * 100), 1);
    }

    /**
     * Average completion rate across enrolled courses (0–100).
     *
     * @param  int[]  $courseIds
     */
    public function getAverageCompletionRate(int $studentId, array $courseIds, Collection $lessonCountsByCourse): int
    {
        if (empty($courseIds)) {
            return 0;
        }

        $completedByCourse = $this->getCompletedLessonsCountByCourse($studentId, $courseIds);
        $percentages = [];

        foreach ($courseIds as $courseId) {
            $total = (int) ($lessonCountsByCourse[$courseId] ?? 0);
            $completed = (int) ($completedByCourse[$courseId] ?? 0);
            $percentages[] = $total > 0 ? round(($completed / $total) * 100, 1) : 0;
        }

        return count($percentages) > 0 ? (int) round(array_sum($percentages) / count($percentages)) : 0;
    }
}
