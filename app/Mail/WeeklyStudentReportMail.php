<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyStudentReportMail extends Mailable implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, SerializesModels;

    public User $student;
    public array $reportData;

    /**
     * Create a new message instance.
     */
    public function __construct(User $student, array $reportData)
    {
        $this->student = $student;
        $this->reportData = $reportData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Weekly Learning Report - Ed-Cademy',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.weekly-student-report',
            with: [
                'student' => $this->student,
                'reportData' => $this->reportData,
                'weekStart' => $this->reportData['week_start'] ?? now()->startOfWeek(),
                'weekEnd' => $this->reportData['week_end'] ?? now()->endOfWeek(),
            ],
        );
    }
}