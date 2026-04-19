<?php

namespace App\Jobs;

use App\Models\Lesson;
use App\Services\VimeoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadLessonVideoToVimeoJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $timeout = 3600;

    public function __construct(public int $lessonId)
    {
    }

    public function handle(VimeoService $vimeo): void
    {
        $lesson = Lesson::find($this->lessonId);
        if (!$lesson || $lesson->type !== 'video') {
            return;
        }

        if (empty($lesson->video_temp_path)) {
            return;
        }

        $relative = $lesson->video_temp_path;
        $absolute = storage_path('app/' . $relative);
        if (!is_file($absolute)) {
            Log::warning('UploadLessonVideoToVimeoJob: temp file missing', ['lesson' => $this->lessonId, 'path' => $relative]);

            return;
        }

        $result = $vimeo->uploadFromDiskPath($absolute, $lesson->title, $lesson->notes);

        if (empty($result['success'])) {
            Log::error('UploadLessonVideoToVimeoJob failed', [
                'lesson' => $this->lessonId,
                'message' => $result['message'] ?? 'unknown',
            ]);
            throw new \RuntimeException($result['message'] ?? 'Vimeo upload failed');
        }

        $lesson->video_path = $result['link'] ?? null;
        $lesson->video_temp_path = null;
        $lesson->save();

        Storage::disk('local')->delete($relative);
    }
}
