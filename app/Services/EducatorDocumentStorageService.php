<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EducatorDocumentStorageService
{
    /**
     * Upload a file directly to S3 under educators/{userId}/{folder}/.
     *
     * @return string Full public S3 URL
     */
    public function uploadToS3(UploadedFile $file, int $userId, string $folder): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'dat');
        $key = "educators/{$userId}/{$folder}/" . Str::uuid() . ($extension ? '.' . $extension : '');

        $stream = fopen($file->getRealPath(), 'rb');
        if ($stream === false) {
            throw new \RuntimeException('Could not read uploaded file.');
        }

        try {
            $uploaded = Storage::disk('s3')->put($key, $stream);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        if (!$uploaded || !Storage::disk('s3')->exists($key)) {
            Log::error('EducatorDocumentStorageService: S3 upload failed', [
                'user' => $userId,
                'key' => $key,
            ]);
            throw new \RuntimeException('Upload to storage failed.');
        }

        return Storage::disk('s3')->url($key);
    }

    /**
     * Delete a file from S3, or from local temp/storage if not a remote URL.
     */
    public function delete(?string $path): void
    {
        if (!$path) {
            return;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $key = $this->s3KeyFromUrl($path);
            if ($key && Storage::disk('s3')->exists($key)) {
                Storage::disk('s3')->delete($key);
            }

            return;
        }

        if (Str::startsWith($path, 'temp/') || Str::startsWith($path, 'storage/')) {
            $absolute = public_path($path);
            if (is_file($absolute)) {
                File::delete($absolute);
            }
        }
    }

    /**
     * Ensure the URL points at this app's S3 bucket and belongs to the educator.
     */
    public function isAllowedForUser(?string $url, int $userId): bool
    {
        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $key = $this->s3KeyFromUrl($url);
        if (!$key) {
            return false;
        }

        $expectedPrefix = "educators/{$userId}/";

        return Str::startsWith($key, $expectedPrefix);
    }

    public function s3KeyFromUrl(string $url): ?string
    {
        $parsed = parse_url($url);
        $path = ltrim($parsed['path'] ?? '', '/');

        if ($path === '') {
            return null;
        }

        $bucket = config('filesystems.disks.s3.bucket');
        if ($bucket && Str::startsWith($path, $bucket . '/')) {
            return substr($path, strlen($bucket) + 1);
        }

        return $path;
    }
}
