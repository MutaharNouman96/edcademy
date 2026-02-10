<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducatorProfile extends Model
{
    use HasFactory;
    //filable
    protected $fillable = [
        'user_id',
        'primary_subject',
        'teaching_levels',
        'hourly_rate',
        'certifications',
        'preferred_teaching_style',
        'govt_id_path',
        'consent_verified',
        'status',
        'verified_at',
        'verified_by',
        'cv_path',
        'degree_proof_path',
        'intro_video_path',


    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifications()
    {
        return $this->hasMany(EducatorVerification::class);
    }
}
