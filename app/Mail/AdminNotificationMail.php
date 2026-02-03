<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNotificationMail extends Mailable implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, SerializesModels;

    public string $type;
    public array $data;
    public string $subject;
    public string $message;

    /**
     * Create a new message instance.
     */
    public function __construct(string $type, array $data, string $subject, string $message)
    {
        $this->type = $type;
        $this->data = $data;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-notification',
            with: [
                'type' => $this->type,
                'data' => $this->data,
                'message' => $this->message,
                'adminUrl' => route('admin.dashboard'),
            ],
        );
    }
}