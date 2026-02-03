<?php

namespace App\Mail;

use App\Models\Session;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SessionBookedMail extends Mailable implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, SerializesModels;

    public Session $session;
    public bool $isForStudent;

    /**
     * Create a new message instance.
     */
    public function __construct(Session $session, bool $isForStudent = true)
    {
        $this->session = $session;
        $this->isForStudent = $isForStudent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isForStudent
            ? 'Session Booked Successfully - Ed-Cademy'
            : 'New Session Booking - Ed-Cademy';

        return new Envelope(subject: $subject);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.session-booked',
            with: [
                'session' => $this->session,
                'isForStudent' => $this->isForStudent,
                'student' => $this->session->student,
                'educator' => $this->session->educator,
                'dashboardUrl' => $this->isForStudent ? route('student.dashboard') : route('educator.dashboard'),
            ],
        );
    }
}