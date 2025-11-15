<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EducatorWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $educator_name;
    public $dashboard_link;

    /**
     * Create a new message instance.
     */
    public function __construct($educator_name, $dashboard_link)
    {
        $this->educator_name = $educator_name;
        $this->dashboard_link = $dashboard_link;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Welcome to Ed-Cademy — Educator Verification Pending')
            ->view('emails.educator_welcome')
            ->with([
                'educator_name' => $this->educator_name,
                'dashboard_link' => $this->dashboard_link,
            ]);
    }
}
