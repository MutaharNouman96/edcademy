<?php

namespace App\Models\Educator;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationSetting extends Model
{
    use HasFactory;

    protected $table = 'educator_verification_settings';
}
