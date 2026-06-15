<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';


    protected $fillable = [
        'educator_id',
        'student_id',
        'course_id',
        'course_resource_id',
        'session_id',
        'gross_amount',
        'tax_amount',
        'platform_commission',
        'net_amount',
        'currency',
        'payment_method',
        'transaction_id',
        'status',
        'notes',

        //payout related fields
        'is_payout_processed',
        'payout_batch_id',
        'payout_status',
        'is_payout_requested',
        'payout_requested_at',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function educator()
    {
        return $this->belongsTo(User::class, 'educator_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function paymentMethodDetails()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method', 'name');
    }

    public function payoutBatch()
    {
        return $this->belongsTo(PayoutBatch::class, 'payout_batch_id');
    }

    protected $casts = [
        'is_payout_processed'  => 'boolean',
        'is_payout_requested'  => 'boolean',
        'payout_requested_at'  => 'datetime',
    ];
}
