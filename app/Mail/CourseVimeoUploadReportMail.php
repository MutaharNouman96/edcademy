<?php

namespace App\Mail;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CourseVimeoUploadReportMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public Course $course;

    /**
     * Per-lesson result objects from UploadCourseVideosToVimeoJob.
     *
     * @var array<int, array{lesson_id:int,title:string,success:bool,message:string,video_id?:string,link?:string}>
     */
    public array $results;

    public bool $hasError;

    public ?string $note;

    public function __construct(Course $course, array $results, bool $hasError, ?string $note = null)
    {
        $this->course   = $course;
        $this->results  = $results;
        $this->hasError = $hasError;
        $this->note     = $note;
    }

    public function envelope(): Envelope
    {
        $prefix = $this->hasError ? '[FAILED]' : '[SUCCESS]';

        return new Envelope(
            subject: "{$prefix} Vimeo Upload Report — Course #{$this->course->id}: {$this->course->title}",
        );
    }

    public function content(): Content
    {
        $successCount = 0;
        $failureCount = 0;
        foreach ($this->results as $r) {
            if (!empty($r['success'])) {
                $successCount++;
            } else {
                $failureCount++;
            }
        }

        return new Content(
            view: 'emails.course-vimeo-upload-report',
            with: [
                'course'       => $this->course,
                'educator'     => $this->course->educator,
                'results'      => $this->results,
                'hasError'     => $this->hasError,
                'note'         => $this->note,
                'successCount' => $successCount,
                'failureCount' => $failureCount,
                'totalCount'   => count($this->results),
            ],
        );
    }
}
