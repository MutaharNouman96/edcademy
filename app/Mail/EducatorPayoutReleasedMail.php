<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

/**
 * Sent to an educator once their course-sale payouts have been released.
 */
class EducatorPayoutReleasedMail extends Mailable implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, SerializesModels;

    public User $educator;

    /** @var Collection<int, \App\Models\EducatorPayout> */
    public Collection $payouts;

    /**
     * @param  Collection<int, \App\Models\EducatorPayout>  $payouts
     */
    public function __construct(User $educator, Collection $payouts)
    {
        $this->educator = $educator;
        $this->payouts = $payouts;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Earnings Have Been Released - Ed-Cademy'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.educator-payout-released',
            with: [
                'educator'    => $this->educator,
                'payouts'     => $this->payouts,
                'totalAmount' => $this->payouts->sum('amount'),
            ],
        );
    }
}
