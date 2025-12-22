<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_id',
        'model',
        'quantity',
        'price',
        'tax',
        'total',
        'status'
    ];
    protected $appends = ['item_details'];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getItemDetailsAttribute()
    {
        return $this->item;
    }

    public function item()
    {
        return $this->morphTo(__FUNCTION__, 'model', 'item_id');
    }
}
