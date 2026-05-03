<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'active',
    ];

    public function category()
    {
        return $this->belongsTo(CourseCategory::class);
    }
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
    
}
