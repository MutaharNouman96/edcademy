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

/**
 * Sent to the platform admin after a payout batch finishes processing.
 */
class AdminPayoutBatchProcessedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public PayoutBatch $batch,
        public User $educator,
        public bool $success,
        public ?string $errorMessage = null,
    ) {
    }

    public function envelope(): Envelope
    {
        $status = $this->success ? 'Completed' : 'Failed';

        return new Envelope(
            subject: "Payout Batch #{$this->batch->id} {$status} - Ed-Cademy Admin"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-payout-batch-processed',
            with: [
                'batch'        => $this->batch,
                'educator'     => $this->educator,
                'success'      => $this->success,
                'errorMessage' => $this->errorMessage,
            ],
        );
    }
}
