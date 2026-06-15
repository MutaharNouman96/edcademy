<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /** Booking lifecycle statuses. */
    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_CONFIRMED       = 'confirmed';
    public const STATUS_CANCELLED       = 'cancelled';
    public const STATUS_COMPLETED       = 'completed';

    /** Payment statuses. */
    public const PAYMENT_UNPAID   = 'unpaid';
    public const PAYMENT_PAID     = 'paid';
    public const PAYMENT_FAILED   = 'failed';
    public const PAYMENT_REFUNDED = 'refunded';

    protected $fillable = [
        'student_id',
        'educator_id',
        'date',
        'time',
        'duration',
        'subject',
        'message',
        'status',
        // Payment
        'amount',
        'currency',
        'payment_status',
        'stripe_session_id',
        'payment_intent_id',
        'payment_method',
        'payment_details',
        'paid_at',
        // Meeting
        'platform',
        'meeting_link',
        'meeting_id',
        'meeting_password',
        // Reminder
        'reminder_sent_at',
    ];

    protected $casts = [
        'date'             => 'date',
        'duration'         => 'float',
        'amount'           => 'decimal:2',
        'paid_at'          => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function educator()
    {
        return $this->belongsTo(User::class, 'educator_id');
    }

    /**
     * Full start datetime of the session (date + time).
     */
    public function getScheduledAtAttribute()
    {
        return $this->date->copy()->setTimeFromTimeString($this->time);
    }

    /**
     * Session length expressed in minutes (duration is stored in hours).
     */
    public function getDurationMinutesAttribute(): int
    {
        return (int) round(((float) $this->duration) * 60);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }
}
