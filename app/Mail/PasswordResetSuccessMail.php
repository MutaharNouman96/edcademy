<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetSuccessMail extends Mailable implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, SerializesModels;

    public User $user;
    public string $resetTime;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $resetTime = '')
    {
        $this->user = $user;
        $this->resetTime = $resetTime ?: now()->toDateTimeString();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Changed Successfully - Ed-Cademy',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset-success',
            with: [
                'user' => $this->user,
                'resetTime' => $this->resetTime,
                'loginUrl' => route('login'),
                'securityUrl' => $this->user->isEducator() ? route('educator.security.index') : route('profile.edit'),
            ],
        );
    }
}