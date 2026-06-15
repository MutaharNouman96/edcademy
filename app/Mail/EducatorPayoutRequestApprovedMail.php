<?php

namespace App\Mail;

use App\Models\EducatorPayoutRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sent to an educator when an admin approves their payout request and the
 * release job has been scheduled.
 */
class EducatorPayoutRequestApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $educator,
        public EducatorPayoutRequest $payoutRequest,
        public int $delayMinutes,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Payout Request Was Approved - Ed-Cademy'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.educator-payout-request-approved',
            with: [
                'educator'      => $this->educator,
                'payoutRequest' => $this->payoutRequest,
                'delayMinutes'  => $this->delayMinutes,
            ],
        );
    }
}
