<?php

namespace App\Mail;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActivityNotificationMail extends Mailable implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, SerializesModels;

    public Activity $activity;
    public User $recipient;

    /**
     * Create a new message instance.
     */
    public function __construct(Activity $activity, User $recipient)
    {
        $this->activity = $activity;
        $this->recipient = $recipient;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->generateSubject();
        return new Envelope(subject: $subject);
    }

    /**
     * Generate email subject based on activity
     */
    private function generateSubject(): string
    {
        $actor = $this->activity->user->full_name;
        $action = ucfirst($this->activity->action_type);
        $entity = $this->activity->entity_name ?: $this->activity->entity_type;

        return "{$actor} {$action} {$entity} - Ed-Cademy Activity";
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.activity-notification',
            with: [
                'activity' => $this->activity,
                'recipient' => $this->recipient,
                'actor' => $this->activity->user,
                'isAdminRecipient' => $this->recipient->isAdmin(),
                'isEducatorRecipient' => $this->recipient->isEducator(),
                'isStudentRecipient' => $this->recipient->isStudent(),
                'actionIcon' => $this->getActionIcon(),
                'actionColor' => $this->getActionColor(),
            ],
        );
    }

    /**
     * Get icon for the action type
     */
    private function getActionIcon(): string
    {
        return match($this->activity->action_type) {
            'create' => '➕',
            'update' => '✏️',
            'delete' => '🗑️',
            'approve' => '✅',
            'reject' => '❌',
            'publish' => '🚀',
            'unpublish' => '📥',
            'enroll' => '📚',
            'complete' => '🎯',
            'cancel' => '🚫',
            'login' => '🔐',
            'logout' => '👋',
            'send_message' => '💬',
            'post_review' => '⭐',
            'book_session' => '📅',
            'process_payout' => '💰',
            default => '📝',
        };
    }

    /**
     * Get color for the action type
     */
    private function getActionColor(): string
    {
        return match($this->activity->action_type) {
            'create' => '#28a745',
            'update' => '#ffc107',
            'delete' => '#dc3545',
            'approve' => '#28a745',
            'reject' => '#dc3545',
            'publish' => '#17a2b8',
            'unpublish' => '#6c757d',
            'enroll' => '#667eea',
            'complete' => '#28a745',
            'cancel' => '#dc3545',
            'login' => '#28a745',
            'logout' => '#6c757d',
            'send_message' => '#6f42c1',
            'post_review' => '#ffc107',
            'book_session' => '#e83e8c',
            'process_payout' => '#28a745',
            default => '#6c757d',
        };
    }
}