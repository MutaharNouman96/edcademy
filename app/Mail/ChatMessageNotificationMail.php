<?php

namespace App\Mail;

use App\Models\ChatMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChatMessageNotificationMail extends Mailable implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, SerializesModels;

    public ChatMessage $message;
    public $receiver;

    /**
     * Create a new message instance.
     */
    public function __construct(ChatMessage $message, $receiver)
    {
        $this->message = $message;
        $this->receiver = $receiver;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Message from ' . $this->message->sender->full_name . ' - Ed-Cademy',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.chat-message-notification',
            with: [
                'message' => $this->message,
                'sender' => $this->message->sender,
                'receiver' => $this->receiver,
                'chat' => $this->message->chat,
            ],
        );
    }
}