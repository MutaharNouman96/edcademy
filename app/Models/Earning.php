<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    use HasFactory;
    protected $casts = [
        'earned_at' => 'datetime',
    ];
    protected $fillable = [
        'educator_id',
        'payment_id',
        'payout_id',
        'session_id',
        'course_id',
        'course_resource_id',
        'gross_amount',
        'platform_commission',
        'net_amount',
        'currency',
        'source_type',
        'status',
        'description',
        'earned_at',
        'paid_at',
    ];

    public function educator()
    {
        return $this->belongsTo(User::class, 'educator_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function payout()
    {
        return $this->belongsTo(Payout::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function courseResource()
    {
        return $this->belongsTo(Lesson::class, 'course_resource_id');
    }
}
