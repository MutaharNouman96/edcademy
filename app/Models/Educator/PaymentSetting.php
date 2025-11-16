<?php

namespace App\Models\Educator;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'educator_payment_settings';
}
