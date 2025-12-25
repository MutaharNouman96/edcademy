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
}
