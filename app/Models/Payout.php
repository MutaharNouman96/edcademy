<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    protected $fillable = [
        'id',
        'educator_id',
        'amount',
        'status',
        'processed_at',
        'processed_by',
        'description',
        'earning_id',
        'created_at',
        'updated_at',
    ];

    public function educator()
    {
        return $this->belongsTo(User::class);
    }
}
