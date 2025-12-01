<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $table = 'student_profiles';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'bio',
        'phone',
        'language',
        'timezone',
        'interests',
        'email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
