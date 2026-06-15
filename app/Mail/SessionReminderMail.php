<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SessionReminderMail extends Mailable implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public bool $isForStudent;

    /**
     * @param  bool  $isForStudent  true => email addressed to the student, false => to the educator
     */
    public function __construct(Booking $booking, bool $isForStudent = true)
    {
        $this->booking = $booking;
        $this->isForStudent = $isForStudent;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reminder: Your session starts in 30 minutes - Ed-Cademy',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.session-reminder',
            with: [
                // The view refers to the booking as $session for backwards compatibility.
                'session'      => $this->booking,
                'isForStudent' => $this->isForStudent,
                'student'      => $this->booking->student,
                'educator'     => $this->booking->educator,
                'dashboardUrl' => $this->isForStudent ? route('student.dashboard') : route('educator.dashboard'),
            ],
        );
    }
}
