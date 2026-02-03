<?php

namespace App\Mail;

use App\Models\Payout;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EducatorPayoutMail extends Mailable implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, SerializesModels;

    public Payout $payout;
    public bool $isSuccessful;

    /**
     * Create a new message instance.
     */
    public function __construct(Payout $payout, bool $isSuccessful = true)
    {
        $this->payout = $payout;
        $this->isSuccessful = $isSuccessful;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isSuccessful
            ? 'Payment Processed - Ed-Cademy Earnings'
            : 'Payment Processing Failed - Ed-Cademy';

        return new Envelope(subject: $subject);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.educator-payout',
            with: [
                'payout' => $this->payout,
                'educator' => $this->payout->educator,
                'isSuccessful' => $this->isSuccessful,
                'earnings' => $this->payout->earning ?? collect(),
            ],
        );
    }
}