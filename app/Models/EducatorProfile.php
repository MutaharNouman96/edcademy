<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducatorProfile extends Model
{
    use HasFactory;
    //filable
    protected $fillable = ['user_id', 'educator_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifications()
    {
        return $this->hasMany(EducatorVerification::class);
    }
}
