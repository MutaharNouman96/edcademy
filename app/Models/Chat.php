<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Broadcast;
class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id'
    ];

    


    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function user1()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
