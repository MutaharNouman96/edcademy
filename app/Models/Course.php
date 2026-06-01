<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;


    protected $fillable = [
        'course_category_id',
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

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'item_id')
            ->whereIn('model', [self::class, 'App\\Models\\Course']);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function earnings()
    {
        return $this->hasMany(Earning::class);
    }

    public function purchasedItems()
    {
        return $this->morphMany(UserPurchasedItem::class, 'purchasable');
    }

    public static function modelTypeValues(): array
    {
        return [self::class, 'App\\Models\\Course'];
    }

    public function purchaseStats(): array
    {
        $courseTypes = self::modelTypeValues();

        $purchaserIdsFromItems = UserPurchasedItem::query()
            ->whereIn('purchasable_type', $courseTypes)
            ->where('purchasable_id', $this->id)
            ->pluck('user_id');

        $purchaserIdsFromLegacy = CoursePurchase::query()
            ->where('course_id', $this->id)
            ->where('is_active', true)
            ->pluck('student_id');

        $uniquePurchasers = $purchaserIdsFromItems
            ->merge($purchaserIdsFromLegacy)
            ->unique()
            ->count();

        $activeEnrollments = UserPurchasedItem::query()
            ->whereIn('purchasable_type', $courseTypes)
            ->where('purchasable_id', $this->id)
            ->where('active', true)
            ->count()
            + CoursePurchase::query()
                ->where('course_id', $this->id)
                ->where('is_active', true)
                ->count();

        $orderItemsQuery = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('order_items.item_id', $this->id)
            ->whereIn('order_items.model', $courseTypes)
            ->whereIn('orders.status', ['completed', 'paid'])
            ->where('orders.is_active', true);

        $totalRevenue = (clone $orderItemsQuery)->sum('order_items.total');
        $totalSales = (clone $orderItemsQuery)->sum('order_items.quantity');
        $completedOrders = (clone $orderItemsQuery)->distinct('orders.id')->count('orders.id');

        $educatorPayments = EducatorPayment::query()
            ->whereHas('orderItem', function ($query) use ($courseTypes) {
                $query->where('item_id', $this->id)
                    ->whereIn('model', $courseTypes);
            })
            ->where('status', 'completed');

        $platformCommission = (clone $educatorPayments)->sum('platform_commission');
        $educatorNet = (clone $educatorPayments)->sum('net_amount');

        $avgRating = $this->reviews()->avg('rating');
        $reviewCount = $this->reviews()->count();

        return [
            'unique_purchasers' => $uniquePurchasers,
            'active_enrollments' => $activeEnrollments,
            'total_revenue' => (float) $totalRevenue,
            'total_sales' => (int) $totalSales,
            'completed_orders' => (int) $completedOrders,
            'platform_commission' => (float) $platformCommission,
            'educator_net' => (float) $educatorNet,
            'avg_rating' => $avgRating ? round($avgRating, 1) : null,
            'review_count' => (int) $reviewCount,
        ];
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

    public function getSlugAttribute()
    {
        return $this->slug ?? Str::slug($this->title);
    }


   
}
