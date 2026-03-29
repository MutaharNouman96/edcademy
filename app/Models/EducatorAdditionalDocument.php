<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducatorAdditionalDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'educator_id',
        'document_path',
        'document_type',
        'document_name',
        'document_size',
    ];
    public function educator()
    {
        return $this->belongsTo(User::class, 'educator_id');
    }
    public function getDocumentPathAttribute($value)
    {
        return url('storage/' . $value);
    }
}
