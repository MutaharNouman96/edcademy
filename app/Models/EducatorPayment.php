<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducatorPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'educator_id',
        'payment_id',
        'gross_amount',
        'tax_amount',
        'platform_commission',
        'net_amount',
        'currency',
        'order_id',
        'order_item_id',
        'status',
    ];

    public function educator()
    {
        return $this->belongsTo(User::class, 'educator_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
    
}
