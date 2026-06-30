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
        return EducatorProfile::resolveFileUrl($this->attributes['document_path'] ?? null);
    }

    /**
     * @return array<string, mixed>
     */
    public function toDocumentPayload(): array
    {
        $path = $this->document_path;
        $kind = EducatorProfile::fileKind($path);
        $icon = match ($kind) {
            'pdf' => 'bi-file-earmark-pdf',
            'image' => 'bi-file-earmark-image',
            'video' => 'bi-camera-video',
            default => 'bi-file-earmark',
        };

        return [
            'id' => $this->id,
            'type' => 'additional_document',
            'path' => $path,
            'url' => $this->document_url,
            'kind' => $kind,
            'name' => $this->document_name ?: basename(parse_url($path, PHP_URL_PATH) ?: $path),
            'label' => 'Additional Document',
            'icon' => $icon,
            'mime_type' => $this->document_type,
            'size' => $this->document_size,
        ];
    }
}
