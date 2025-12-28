<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducatorPayout extends Model
{
    use HasFactory;
    protected $fillable = [
        'educator_id',
        'payment_id',
        'amount',
        'status',
        'processed_at',
        'processed_by',
        'description',       
    ];
    public function educator()
    {
        return $this->belongsTo(User::class, 'educator_id');
    }
    public function payment()
    {
        return $this->belongsTo(EducatorPayment::class, 'payment_id');
    }
}
