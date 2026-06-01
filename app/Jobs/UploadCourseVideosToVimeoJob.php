<?php

namespace App\Jobs;

use App\Mail\CourseVimeoUploadReportMail;
use App\Models\Course;
use App\Models\Lesson;
use App\Services\VimeoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Scheduled 5 minutes after an admin approves a course.
 * Iterates every video lesson in the course that still has a local temp file
 * (video_temp_path) and uploads it to Vimeo, then emails a summary report to
 * the platform admin.
 *
 * This job is intentionally self-contained: per-lesson failures are captured
 * and do NOT throw, so one broken file does not stop the rest of the course
 * from uploading. A summary email is always sent at the end.
 */
class UploadCourseVideosToVimeoJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** Admin recipient for success/failure reports. */
    public const ADMIN_EMAIL = 'admin@ed-cademy.com';

    public int $tries = 2;

    /** Allow up to 2 hours for courses with many long videos. */
    public int $timeout = 7200;

    public int $courseId;

    public function __construct(int $courseId)
    {
        $this->courseId = $courseId;
    }

    public function handle(VimeoService $vimeo): void
    {
        $function = __METHOD__;
        $course = Course::find($this->courseId);

        if (!$course) {
            Log::warning("[{$function}] UploadCourseVideosToVimeoJob: course not found", [
                'function' => $function,
                'course_id' => $this->courseId,
            ]);

            return;
        }

        $lessons = Lesson::where('course_id', $course->id)
            ->where('type', 'video')
            ->whereNotNull('video_temp_path')
            ->get();

        Log::info("[{$function}] UploadCourseVideosToVimeoJob: starting", [
            'function'        => $function,
            'course_id'        => $course->id,
            'course_title'     => $course->title,
            'pending_uploads'  => $lessons->count(),
        ]);

        $results = [];

        if ($lessons->isEmpty()) {
            Log::info("[{$function}] UploadCourseVideosToVimeoJob: no pending videos", [
                'function' => $function,
                'course_id' => $course->id,
            ]);

            $this->sendAdminReport($course, $results, hasError: false, note: 'No pending video lessons found for this course.');

            return;
        }

        foreach ($lessons as $lesson) {
            $results[] = $this->uploadSingleLesson($vimeo, $lesson);
        }

        $this->sendAdminReport($course, $results, hasError: $this->anyFailures($results));
        //log all results
        Log::info("[{$function}] UploadCourseVideosToVimeoJob: all results", [
            'function' => $function,
            'results' => $results,
        ]);
    }

    /**
     * Upload a single lesson's temp video to Vimeo. Never throws — returns a
     * structured result that we later roll up into the admin email.
     *
     * @return array{lesson_id:int,title:string,success:bool,message:string,video_id?:string,link?:string}
     */
    protected function uploadSingleLesson(VimeoService $vimeo, Lesson $lesson): array
    {
        $function = __METHOD__;
        $base = [
            'lesson_id' => $lesson->id,
            'title'     => (string) $lesson->title,
        ];

        $relative = $lesson->video_temp_path;
        $absolute = public_path('storage/' . $relative);

        if (!is_file($absolute)) {
            $msg = "Temp file missing on disk: {$relative}";
            Log::warning("[{$function}] UploadCourseVideosToVimeoJob: " . $msg . " path: {$absolute}", array_merge($base, [
                'function' => $function,
            ]));

            return array_merge($base, [
                'success' => false,
                'message' => $msg,
                'path' => $absolute,
            ]);
        }

        try {
            $result = $vimeo->uploadFromDiskPath(
                $absolute,
                (string) $lesson->title,
                $lesson->notes
            );
            //log the result
            Log::info("[{$function}] UploadCourseVideosToVimeoJob: upload result", [
                'function' => $function,
                'result' => $result,
            ]);
        } catch (Throwable $e) {
            Log::error("[{$function}] UploadCourseVideosToVimeoJob: exception during upload", array_merge($base, [
                'function' => $function,
                'error' => $e->getMessage(),
            ]));

            return array_merge($base, [
                'success' => false,
                'message' => 'Upload threw exception: ' . $e->getMessage(),
                'path' => $absolute,
            ]);
        }

        if (empty($result['success'])) {
            Log::error("[{$function}] UploadCourseVideosToVimeoJob: upload failed", array_merge($base, [
                'function' => $function,
                'vimeo_response' => $result,
                'path' => $absolute,
            ]));

            return array_merge($base, [
                'success' => false,
                'message' => $result['message'] ?? 'Vimeo upload failed (unknown reason)',
                'path' => $absolute,
            ]);
        }

        $lesson->video_path = $result['link'] ?? null;
        $lesson->video_link = $result['link'] ?? null;
        $lesson->video_temp_path = null;
        $lesson->save();
        //log the lesson id, title, video_path, video_link
        Log::info("[{$function}] UploadCourseVideosToVimeoJob: lesson uploaded successfully", [
            'function' => $function,
            'lesson_id' => $lesson->id,
            'title' => $lesson->title,
            'video_path' => $lesson->video_path,
            'video_link' => $lesson->video_link,
        ]);

        try {
            $tempAbs = public_path('storage/' . $relative);
            if (is_file($tempAbs)) {
                File::delete($tempAbs);
            }
        } catch (Throwable $e) {
            Log::warning("[{$function}] UploadCourseVideosToVimeoJob: could not delete temp file", array_merge($base, [
                'function' => $function,
                'path'  => $relative,
                'temp_path' => $tempAbs,
                'error' => $e->getMessage(),
            ]));
        }

        Log::info("[{$function}] UploadCourseVideosToVimeoJob: upload succeeded", array_merge($base, [
            'function' => $function,
            'video_id' => $result['video_id'] ?? null,
            'link'     => $result['link'] ?? null,
            'path' => $absolute,
        ]));

        return array_merge($base, [
            'success'  => true,
            'message'  => 'Uploaded successfully',
            'video_id' => $result['video_id'] ?? null,
            'link'     => $result['link'] ?? null,
        ]);
    }

    protected function anyFailures(array $results): bool
    {
        $function = __METHOD__;
        foreach ($results as $r) {
            if (empty($r['success'])) {
                // You may add a log here if detailed tracing of failures is needed for debugging:
                // Log::debug("[{$function}] Detected failure in results", ['function' => $function, 'result' => $r]);
                return true;
            }
        }

        return false;
    }

    protected function sendAdminReport(Course $course, array $results, bool $hasError, ?string $note = null): void
    {
        $function = __METHOD__;
        try {
            Mail::to(self::ADMIN_EMAIL)->send(
                new CourseVimeoUploadReportMail($course, $results, $hasError, $note)
            );
        } catch (Throwable $e) {
            Log::error("[{$function}] UploadCourseVideosToVimeoJob: failed to send admin report email", [
                'function' => $function,
                'course_id' => $course->id,
                'error'     => $e->getMessage(),
            ]);
        }
    }

    /**
     * Laravel invokes this when the job exhausts retries or throws fatally.
     * We still want the admin to hear about it.
     */
    public function failed(Throwable $exception): void
    {
        $function = __METHOD__;
        Log::critical("[{$function}] UploadCourseVideosToVimeoJob: job permanently failed", [
            'function' => $function,
            'course_id' => $this->courseId,
            'error'     => $exception->getMessage(),
        ]);

        $course = Course::find($this->courseId);
        if (!$course) {
            return;
        }

        try {
            Mail::to(self::ADMIN_EMAIL)->send(
                new CourseVimeoUploadReportMail(
                    $course,
                    [],
                    hasError: true,
                    note: 'Job crashed before completing. Error: ' . $exception->getMessage()
                )
            );
        } catch (Throwable $e) {
            Log::error("[{$function}] UploadCourseVideosToVimeoJob::failed: could not email admin", [
                'function' => $function,
                'course_id' => $this->courseId,
                'error'     => $e->getMessage(),
            ]);
        }
    }
}
