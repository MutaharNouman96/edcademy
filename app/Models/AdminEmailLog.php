<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminEmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'status',
        'recipient_email',
        'subject',
        'meta',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'sent_at' => 'datetime',
    ];
}
