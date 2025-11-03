<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducatorVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'educator_profile_id',
        'document_type',
        'document_path',
        'status', // pending, approved, rejected
        'reviewed_by', // admin user id
        'reviewed_at',
        'rejection_reason',
    ];
    public function educatorProfile()
    {
        return $this->belongsTo(EducatorProfile::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
