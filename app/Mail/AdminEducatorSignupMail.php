<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminEducatorSignupMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $educator;

    /**
     * Create a new message instance.
     */
    public function __construct($educator)
    {
        $this->educator = $educator;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Educator Signup — Verification Required')
            ->view('emails.admin_educator_signup')
            ->with(['educator' => $this->educator]);
    }
}
