<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
class Policy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'content',
    ];

    protected static function booted()
    {
        static::creating(function (Policy $policy) {
            if (empty($policy->slug)) {
                $policy->slug = self::makeUniqueSlug($policy->name);
            }
        });

        static::updating(function (Policy $policy) {
            if (empty($policy->slug) && !empty($policy->name)) {
                $policy->slug = self::makeUniqueSlug($policy->name, $policy->id);
            }
        });
    }

    private static function makeUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
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

