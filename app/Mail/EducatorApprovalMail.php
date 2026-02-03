<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EducatorApprovalMail extends Mailable implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, SerializesModels;

    public User $educator;

    /**
     * Create a new message instance.
     */
    public function __construct(User $educator)
    {
        $this->educator = $educator;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Congratulations! Your Ed-Cademy Educator Account is Approved',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.educator-approved',
            with: [
                'educator' => $this->educator,
                'dashboardUrl' => route('educator.dashboard'),
                'coursesUrl' => route('educator.courses.index'),
            ],
        );
    }
}