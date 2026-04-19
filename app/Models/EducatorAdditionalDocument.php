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

    protected $appends = [
        'document_url',
    ];

    public function educator()
    {
        return $this->belongsTo(User::class, 'educator_id');
    }

    /**
     * Public URL for the stored file (document_path stays the relative path under /public).
     */
    public function getDocumentUrlAttribute(): ?string
    {
        $path = $this->attributes['document_path'] ?? null;
        if (! $path) {
            return null;
        }
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return asset($path);
    }
}
