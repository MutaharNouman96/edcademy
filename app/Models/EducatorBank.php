<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducatorBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'educator_id',
        'bank_name',
        'account_name',
        'iban',
        'approval_status',
        'approved_at'
    ];

    public function educator()
    {
        return $this->belongsTo(User::class);
    }
}
