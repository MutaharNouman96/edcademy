<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EducatorRejectionMail extends Mailable implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, SerializesModels;

    public User $educator;
    public string $reason;

    /**
     * Create a new message instance.
     */
    public function __construct(User $educator, string $reason = '')
    {
        $this->educator = $educator;
        $this->reason = $reason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ed-Cademy Educator Application Update',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.educator-rejected',
            with: [
                'educator' => $this->educator,
                'reason' => $this->reason,
                'supportUrl' => route('web.contact.us'),
                'retryUrl' => route('educator.become-an-educator'),
            ],
        );
    }
}