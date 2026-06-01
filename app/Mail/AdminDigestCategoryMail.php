<?php

namespace App\Mail;

use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class AdminDigestCategoryMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $title;
    public Collection $rows;
    public CarbonInterface $from;
    public CarbonInterface $to;

    /**
     * Create a new message instance.
     */
    public function __construct(string $title, Collection $rows, CarbonInterface $from, CarbonInterface $to)
    {
        $this->title = $title;
        $this->rows = $rows;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: sprintf('%s Digest (Last 12 Hours) - %d', $this->title, $this->rows->count()),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.notifications-digest',
            with: [
                'title' => $this->title,
                'rows' => $this->rows,
                'from' => $this->from,
                'to' => $this->to,
            ],
        );
    }
}
