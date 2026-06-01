<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_section_id',
        'course_id',
        'title',
        'type',
        'category',
        'video_link',
        'video_path',
        'description',
        'duration',
        'is_preview',
        'preview',
        'order',
        'materials',
        'worksheets',
        'resources',
        'assignments',
        'notes',
        'status',
        // Admin-controlled visibility flag (see scopes below).
        'active',
        'price',
        'free',
        'published_at',
        'thumbnail',
        'popular',
    ];
    protected $casts = [
        'published_at' => 'datetime',
        'active' => 'boolean',
    ];

    protected $appends = ["materials_path", "worksheets_path", "lesson_video_path", "uses_direct_video_player"];


    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function courseSection()
    {
        return $this->belongsTo(CourseSection::class);
    }

    public function purchasers()
    {
        return $this->morphToMany(
            User::class,
            'purchasable',
            'user_purchased_items'
        )->withPivot('active')->withTimestamps();
    }

    public function scopepublished($query)
    {
        return $query->where('status', 'Published');
    }

    /**
     * Only lessons an admin has verified/approved. Used everywhere the lesson
     * is surfaced publicly (course listing, course details, student player).
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Lessons still awaiting admin verification.
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }

    public function lesson_video_views()
    {
        return $this->hasMany(LessonVideoViews::class);
    }

    public function lesson_video_comments()
    {
        return $this->hasMany(LessonVideoComment::class);
    }

    public function getMaterialsPathAttribute()
    {
        if (str_contains($this->materials, 's3.amazonaws.com')) {   
            $path = parse_url($this->materials, PHP_URL_PATH);
            $key = ltrim($path, '/'); // lessons/materials/abc123.pdf

            return Storage::disk('s3')->temporaryUrl($key, now()->addMinutes(30));
        }
        return  asset('storage/' . $this->materials);
    }

    public function getWorksheetsPathAttribute()
    {
        if (str_contains($this->worksheets, 's3.amazonaws.com')) {
              $path = parse_url($this->worksheets, PHP_URL_PATH);
        $key = ltrim($path, '/'); // lessons/worksheets/abc123.pdf

            return Storage::disk('s3')->temporaryUrl($key, now()->addMinutes(30));
        }
        return  asset('storage/' . $this->worksheets);
    }

    public function getUsesDirectVideoPlayerAttribute(): bool
    {
        // Uploaded videos always live on S3 now and play in the native player.
        if ($this->video_path) {
            return true;
        }

        if (!$this->video_link) {
            return false;
        }

        return !$this->isEmbedVideoLink($this->video_link);
    }

    public function getLessonVideoPathAttribute()
    {
        // Uploaded video (stored as an S3 URL) takes precedence over an external link.
        if ($this->video_path) {
            return $this->resolveStoredVideoUrl($this->video_path);
        }
        if ($this->video_link) {
            return $this->video_link;
        }

        return null;
    }

    private function resolveStoredVideoUrl(string $path): string
    {
        if (!str_starts_with($path, 'http')) {
            return asset('storage/' . $path);
        }

        $s3Key = $this->extractS3ObjectKey($path);
        if ($s3Key) {
            return Storage::disk('s3')->temporaryUrl($s3Key, now()->addMinutes(30));
        }

        return $path;
    }

    private function extractS3ObjectKey(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (!$path) {
            return null;
        }

        $key = ltrim($path, '/');
        if ($key === '' || !str_starts_with($key, 'lessons/videos/')) {
            return null;
        }

        return $key;
    }

    private function isEmbedVideoLink(string $url): bool
    {
        $embedHosts = ['youtube.com', 'youtu.be', 'vimeo.com', 'player.vimeo.com'];

        foreach ($embedHosts as $host) {
            if (str_contains($url, $host)) {
                return true;
            }
        }

        return false;
    }
}
