<?php

namespace App\Mail;

use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CourseCertificateMail extends Mailable implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, SerializesModels;

    public Course $course;
    public User $student;
    public $certificateData;

    /**
     * Create a new message instance.
     */
    public function __construct(Course $course, User $student, $certificateData = [])
    {
        $this->course = $course;
        $this->student = $student;
        $this->certificateData = $certificateData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Congratulations! Your Course Certificate is Ready',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.course-certificate',
            with: [
                'course' => $this->course,
                'student' => $this->student,
                'educator' => $this->course->user,
                'certificateData' => $this->certificateData,
                'completionDate' => $this->certificateData['completion_date'] ?? now(),
                'certificateUrl' => $this->certificateData['certificate_url'] ?? '#',
            ],
        );
    }
}