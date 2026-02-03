<?php

namespace App\Mail;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CourseRejectionMail extends Mailable implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, SerializesModels;

    public Course $course;
    public string $reason;

    /**
     * Create a new message instance.
     */
    public function __construct(Course $course, string $reason = '')
    {
        $this->course = $course;
        $this->reason = $reason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Course Review Update - Action Required',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.course-rejected',
            with: [
                'course' => $this->course,
                'educator' => $this->course->user,
                'reason' => $this->reason,
                'dashboardUrl' => route('educator.dashboard'),
                'supportUrl' => route('web.contact.us'),
            ],
        );
    }
}