<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'sender_id',
        'message',
        'type',
        'file',
        'read'
    ];
    protected $appends = ['human_time'];


    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function getHumanTimeAttribute()
    {
        $created = Carbon::parse($this->created_at);
        $now = Carbon::now();

        // If within 24 hours, show "x hours ago"
        if ($created->diffInHours($now) < 24) {
            return $created->diffForHumans();
        }

        // Otherwise show full formatted date
        return $created->format('M d, Y · h:i A');
    }
}
