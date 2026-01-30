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
        'status',
        'approval_status',
        'review_note'
    ];
    protected $appends = ['thumbnail_path'];


    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function sections()
    {
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

    public function reviews()
    {
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
        return $query->where('status', 'published')->where('publish_option', '!=', 'draft')->where('active', true);
    }

    public function scopeBestReviewed($query)
    {
        return $query
            ->withAvg('reviews', 'rating')   // calculates avg rating
            ->orderBy('reviews_avg_rating', 'desc')  // sort by highest avg
            ->withCount('reviews');
    }


    public function purchasers()
    {
        return $this->morphToMany(
            User::class,
            'purchasable',
            'user_purchased_items'
        )->withPivot('active')->withTimestamps();
    }


    public function getThumbnailPathAttribute()
    {
        return asset( $this->thumbnail);
    }


   
}
