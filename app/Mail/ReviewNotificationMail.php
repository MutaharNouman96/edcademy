<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReviewNotificationMail extends Mailable implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, SerializesModels;

    public $review;
    public string $reviewType; // 'course' or 'educator'
    public bool $isForRecipient; // true for educator/course owner, false for reviewer

    /**
     * Create a new message instance.
     */
    public function __construct($review, string $reviewType, bool $isForRecipient = true)
    {
        $this->review = $review;
        $this->reviewType = $reviewType;
        $this->isForRecipient = $isForRecipient;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isForRecipient
            ? "New {$this->reviewType} review received"
            : "Your {$this->reviewType} review has been posted";

        return new Envelope(subject: $subject);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.review-notification',
            with: [
                'review' => $this->review,
                'reviewType' => $this->reviewType,
                'isForRecipient' => $this->isForRecipient,
            ],
        );
    }
}