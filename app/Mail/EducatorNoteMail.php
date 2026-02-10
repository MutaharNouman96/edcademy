<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EducatorNoteMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $educatorName;
    public string $noteSubject;
    public string $noteBody;

    public function __construct(string $educatorName, string $noteSubject, string $noteBody)
    {
        $this->educatorName = $educatorName;
        $this->noteSubject = $noteSubject;
        $this->noteBody = $noteBody;
    }

    public function build()
    {
        return $this->subject($this->noteSubject)
            ->view('emails.educator_note');
    }
}
