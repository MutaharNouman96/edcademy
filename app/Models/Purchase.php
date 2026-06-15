<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'status',
        'payment_status',
        'payment_method',
        'payment_id',
        'is_active',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function isPaid(): bool
    {
        return in_array(strtolower((string) $this->payment_status), ['paid', 'completed', 'approved'], true);
    }
}
