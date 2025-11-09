<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['educator_id', 'student_id', 'course_id', 'session_id', 'gross_amount', 'tax_amount', 'platform_commission', 'net_amount', 'currency', 'payment_method', 'transaction_id', 'status', 'notes'];

    public function educator()
    {
        return $this->belongsTo(User::class, 'educator_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function courseResource()
    {
        return $this->belongsTo(Lesson::class , 'id' , 'course_resource_id');
    }
}
