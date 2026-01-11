<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

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

    protected $table = 'payments';
}
