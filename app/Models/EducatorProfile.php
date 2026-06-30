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
     * Verification document type definitions (single source of truth for upload rules + UI).
     *
     * @return array<string, array<string, mixed>>
     */
    public static function verificationDocumentTypes(): array
    {
        return [
            'cv' => [
                'label' => 'CV',
                'icon' => 'bi-file-earmark-person',
                'column' => 'cv_path',
                'folder' => 'cv',
                'mimes' => 'jpeg,png,jpg,gif,webp,pdf',
                'accept' => 'image/jpeg,image/png,image/gif,image/webp,application/pdf',
                'max_kb' => 5120,
                'hint' => 'JPG, PNG, or PDF — max 5MB.',
                'multiple' => false,
            ],
            'gov_id' => [
                'label' => 'Government ID',
                'icon' => 'bi-person-badge',
                'column' => 'govt_id_path',
                'folder' => 'gov_ids',
                'mimes' => 'jpeg,png,jpg,gif,webp,pdf',
                'accept' => 'image/jpeg,image/png,image/gif,image/webp,application/pdf',
                'max_kb' => 5120,
                'hint' => 'JPG, PNG, or PDF — max 5MB.',
                'multiple' => false,
            ],
            'degree_proof' => [
                'label' => 'Teaching Credential',
                'icon' => 'bi-mortarboard',
                'column' => 'degree_proof_path',
                'folder' => 'degrees',
                'mimes' => 'jpeg,png,jpg,gif,webp,pdf',
                'accept' => 'image/jpeg,image/png,image/gif,image/webp,application/pdf',
                'max_kb' => 5120,
                'hint' => 'JPG, PNG, or PDF — max 5MB. Optional.',
                'multiple' => false,
            ],
            'intro_video' => [
                'label' => 'Intro Video',
                'icon' => 'bi-camera-video',
                'column' => 'intro_video_path',
                'folder' => 'videos',
                'mimetypes' => 'video/mp4,video/quicktime',
                'accept' => 'video/mp4,video/quicktime',
                'max_kb' => 51200,
                'hint' => 'MP4 or MOV — max 50MB. Optional.',
                'multiple' => false,
            ],
            'additional_document' => [
                'label' => 'Additional Document',
                'icon' => 'bi-paperclip',
                'folder' => 'additional_documents',
                'mimes' => 'jpeg,png,jpg,gif,webp,pdf',
                'accept' => 'image/jpeg,image/png,image/gif,image/webp,application/pdf',
                'max_kb' => 5120,
                'max_files' => 10,
                'hint' => 'PDF or images, up to 10 files, each max 5MB.',
                'multiple' => true,
            ],
        ];
    }

    public static function verificationDocumentType(string $type): ?array
    {
        return self::verificationDocumentTypes()[$type] ?? null;
    }

    /**
     * Build preview payload for a stored path.
     *
     * @return array<string, mixed>|null
     */
    public static function documentPayload(?string $path, ?string $label = null, ?string $icon = null): ?array
    {
        if (!$path) {
            return null;
        }

        $name = basename(parse_url($path, PHP_URL_PATH) ?: $path);

        return [
            'path' => $path,
            'url' => self::resolveFileUrl($path),
            'kind' => self::fileKind($path),
            'name' => $name,
            'label' => $label,
            'icon' => $icon,
        ];
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
