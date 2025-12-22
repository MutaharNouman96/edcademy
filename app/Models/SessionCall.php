<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'educator_id',
        'title',
        'start_time',
        'end_time',
        'meeting_link',
        'status',
        'is_paid',
        'price',
        
    ];

    public function educator()
    {
        return $this->belongsTo(User::class, 'educator_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'session_users', 'session_id', 'user_id')
            ->withPivot(['role', 'status']);
    }
}
