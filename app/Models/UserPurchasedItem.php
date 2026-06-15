<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class UserPurchasedItem extends MorphPivot
{
    protected $table = 'user_purchased_items';

    protected $fillable = [
        'user_id',
        'purchasable_id',
        'purchasable_type',
        'active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchasable()
    {
        return $this->morphTo(); // to course or lesson
    }

    /**
     * Human-readable label for payment history / invoices.
     */
    public function displayTitle(): string
    {
        $item = $this->purchasable;

        if (! $item) {
            return 'Purchase #' . $this->id;
        }

        return $item->title ?? ('Item #' . $this->purchasable_id);
    }

    /**
     * Best-effort price from the underlying purchasable record.
     */
    public function displayPrice(): float
    {
        return (float) ($this->purchasable?->price ?? 0);
    }

    public function educator()
    {
        return $this->hasOneThrough(
            User::class,
            Course::class,
            'id',        // Course.id
            'id',        // User.id
            'purchasable_id',
            'user_id'
        )->where('purchasable_type', Course::class);
    }

    
}
