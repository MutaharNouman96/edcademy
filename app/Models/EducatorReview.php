<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducatorReview extends Model
{
    use HasFactory;
    protected $fillable = [
        'educator_id',
        'student_id',
        'rating',
        'comment'
    ];

    public function educator()
    {
        return $this->belongsTo(User::class, 'educator_id');
    }
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
