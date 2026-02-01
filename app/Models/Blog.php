<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'author',
        'status',
        'image',
        'tags',
    ];

    protected static function booted()
    {
        static::creating(function (Blog $blog) {
            if (empty($blog->slug)) {
                $blog->slug = self::makeUniqueSlug($blog->title);
            }
        });

        static::updating(function (Blog $blog) {
            if (empty($blog->slug) && !empty($blog->title)) {
                $blog->slug = self::makeUniqueSlug($blog->title, $blog->id);
            }
        });
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        // Project convention: assets accessed under /public/...
        return url('public/' . ltrim($this->image, '/'));
    }

    public function getTagsArrayAttribute(): array
    {
        if (!$this->tags) {
            return [];
        }

        return collect(explode(',', (string) $this->tags))
            ->map(fn ($t) => trim($t))
            ->filter()
            ->values()
            ->all();
    }

    public function getExcerptAttribute(): string
    {
        $text = trim(strip_tags((string) $this->content));
        return Str::limit($text, 180);
    }

    private static function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base ?: Str::random(8);

        $i = 1;
        while (static::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()
        ) {
            $i++;
            $slug = $base . '-' . $i;
        }

        return $slug;
    }
}
