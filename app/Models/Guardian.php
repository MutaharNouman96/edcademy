<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'guardian_name',
        'guardian_contact',
        'guardian_relation',
        'is_verified',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
