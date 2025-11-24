<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;


    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'description',
        'subject',
        'level',
        'price',
        'duration',
        'difficulty',
        'type',
        'thumbnail',
        'tags',
        'publish_option',
        'publish_date',
        'status'
    ];


    public function category()
    {
        return $this->belongsTo(CourseCategory::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function sections(){
        return $this->hasMany(CourseSection::class);
    }

    public function features()
    {
        return $this->hasOne(CourseFeature::class);
    }

    public function educator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviews(){
        return $this->hasMany(CourseReview::class);
    }

    public function coursePurchases()
    {
        return $this->hasMany(CoursePurchase::class);
    }


    public function scopeactive($query)
    {
        return $query->where('active', true);
    }

    public function scopepublished($query)
    {
        return $query->where('status', 'published');
    }
}
