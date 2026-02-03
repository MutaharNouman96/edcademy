<?php

namespace App\Mail;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CourseApprovalMail extends Mailable implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, SerializesModels;

    public Course $course;

    /**
     * Create a new message instance.
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Congratulations! Your Course is Now Live on Ed-Cademy',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.course-approved',
            with: [
                'course' => $this->course,
                'educator' => $this->course->user,
                'courseUrl' => route('web.course.show', ['slug' => $this->course->slug ?? 'course', 'id' => $this->course->id]),
                'dashboardUrl' => route('educator.dashboard'),
            ],
        );
    }
}