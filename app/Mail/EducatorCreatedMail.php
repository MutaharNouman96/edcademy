<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EducatorCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $educatorName;
    public string $email;
    public string $plainPassword;
    public string $loginUrl;

    public function __construct(string $educatorName, string $email, string $plainPassword, string $loginUrl)
    {
        $this->educatorName = $educatorName;
        $this->email = $email;
        $this->plainPassword = $plainPassword;
        $this->loginUrl = $loginUrl;
    }

    public function build()
    {
        return $this->subject('Your Educator Account Has Been Created — Ed-Cademy')
            ->view('emails.educator_created');
    }
}
