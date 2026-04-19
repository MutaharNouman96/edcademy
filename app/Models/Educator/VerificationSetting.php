<?php

namespace App\Models\Educator;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationSetting extends Model
{
    use HasFactory;

    protected $table = 'educator_verification_settings';

    protected $fillable = [
        'educator_id',
        'gov_id_file',
        'credential_file',
        'business_type',
        'tos',
    ];

    protected $casts = [
        'tos' => 'boolean',
    ];
}
