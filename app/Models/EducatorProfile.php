<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducatorProfile extends Model
{
    use HasFactory;
    //filable
    protected $fillable = [
        'user_id',
        'primary_subject',
        'educator_type',
        'teaching_levels',
        'hourly_rate',
        'max_sessions_per_day',
        'certifications',
        'preferred_teaching_style',
        'govt_id_path',
        'consent_verified',
        'status',
        'verified_at',
        'verified_by',
        'cv_path',
        'degree_proof_path',
        'intro_video_path',


    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifications()
    {
        return $this->hasMany(EducatorVerification::class);
    }

    /**
     * Resolve a stored path (S3 URL, temp/, storage/, or legacy key) to a public URL.
     */
    public static function resolveFileUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        if (str_starts_with($path, 'storage/') || str_starts_with($path, 'temp/')) {
            return asset($path);
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    /**
     * Guess preview kind for modal rendering (image, pdf, video, other).
     */
    public static function fileKind(?string $path): string
    {
        if (!$path) {
            return 'other';
        }

        $pathOnly = parse_url($path, PHP_URL_PATH) ?: $path;
        $ext = strtolower(pathinfo($pathOnly, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            return 'image';
        }

        if ($ext === 'pdf') {
            return 'pdf';
        }

        if (in_array($ext, ['mp4', 'mov', 'webm', 'avi', 'mkv'], true)) {
            return 'video';
        }

        return 'other';
    }

    /**
     * Decode preferred_teaching_style whether stored as plain text or JSON-encoded.
     */
    public function decodedTeachingStyle(): string
    {
        $style = $this->preferred_teaching_style ?? '';

        if (is_string($style) && str_starts_with(trim($style), '"')) {
            $decoded = json_decode($style);

            return is_string($decoded) ? $decoded : $style;
        }

        return (string) $style;
    }
}
