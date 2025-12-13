<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'item_id',
        'model',
        'quantity',
        'price',
        'tax',
        'total',
    ];
    
    protected $appends = ['item_details'];
    public function getItemDetailsAttribute()
    {
        return $this->item;
    }

    public function item()
    {
        return $this->morphTo(__FUNCTION__, 'model', 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
