<?php

namespace App\Mail;

use App\Models\PayoutBatch;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

/**
 * Sent to an educator after a PayoutBatch has been successfully released.
 */
class EducatorPayoutBatchReleasedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $educator,
        public PayoutBatch $batch,
        public Collection $payments,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Payout Has Been Released - Ed-Cademy'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.educator-payout-batch-released',
            with: [
                'educator'    => $this->educator,
                'batch'       => $this->batch,
                'payments'    => $this->payments,
                'totalAmount' => $this->batch->total_net_amount,
                'currency'    => $this->batch->currency,
            ],
        );
    }
}
