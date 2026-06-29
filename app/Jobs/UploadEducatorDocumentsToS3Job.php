<?php

namespace App\Jobs;

use App\Models\EducatorAdditionalDocument;
use App\Models\EducatorProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Moves an educator's freshly-submitted documents from the local public/temp
 * scratch area to S3, swaps the stored paths for permanent S3 URLs, and cleans
 * up the temp files. Dispatched with a one-minute delay after signup.
 */
class UploadEducatorDocumentsToS3Job implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $timeout = 1800;

    /**
     * Only files parked here are eligible to be moved (prevents re-uploading a
     * value that is already an S3 URL on a retry).
     */
    private const TEMP_PREFIX = 'temp/educators/';

    public function __construct(public int $userId)
    {
    }

    public function handle(): void
    {
        $profile = EducatorProfile::where('user_id', $this->userId)->first();

        if (!$profile) {
            Log::warning('UploadEducatorDocumentsToS3Job: profile not found', ['user' => $this->userId]);

            return;
        }

        // profile column => S3 sub-folder
        $columns = [
            'cv_path' => 'cv',
            'govt_id_path' => 'gov_ids',
            'degree_proof_path' => 'degrees',
            'intro_video_path' => 'videos',
        ];

        foreach ($columns as $column => $folder) {
            $temp = $profile->{$column};
            if ($this->isTempPath($temp)) {
                $url = $this->moveToS3($temp, "educators/{$this->userId}/{$folder}");
                if ($url) {
                    $profile->{$column} = $url;
                }
            }
        }

        $profile->save();

        $documents = EducatorAdditionalDocument::where('educator_id', $this->userId)->get();
        foreach ($documents as $document) {
            if ($this->isTempPath($document->document_path)) {
                $url = $this->moveToS3($document->document_path, "educators/{$this->userId}/additional_documents");
                if ($url) {
                    $document->document_path = $url;
                    $document->save();
                }
            }
        }
    }

    /**
     * Stream a local temp file to S3 and return its full URL, deleting the temp
     * copy on success. Returns null (and keeps the temp file) on failure so a
     * retry can pick it up again.
     */
    private function moveToS3(string $relativePath, string $s3Dir): ?string
    {
        $absolute = public_path($relativePath);

        if (!is_file($absolute)) {
            Log::warning('UploadEducatorDocumentsToS3Job: temp file missing', [
                'user' => $this->userId,
                'path' => $relativePath,
            ]);

            return null;
        }

        $extension = pathinfo($absolute, PATHINFO_EXTENSION);
        $key = $s3Dir . '/' . Str::uuid() . ($extension ? '.' . $extension : '');

        $stream = fopen($absolute, 'rb');
        if ($stream === false) {
            Log::error('UploadEducatorDocumentsToS3Job: could not open temp file', [
                'user' => $this->userId,
                'path' => $relativePath,
            ]);

            return null;
        }

        try {
            $uploaded = Storage::disk('s3')->put($key, $stream);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        if (!$uploaded || !Storage::disk('s3')->exists($key)) {
            Log::error('UploadEducatorDocumentsToS3Job: S3 upload failed', [
                'user' => $this->userId,
                's3_key' => $key,
                'source' => $relativePath,
            ]);

            return null;
        }

        $this->deleteLocalTempFile($relativePath);

        return Storage::disk('s3')->url($key);
    }

    /**
     * Remove a successfully uploaded scratch file from public/temp.
     */
    private function deleteLocalTempFile(string $relativePath): void
    {
        if (!$this->isTempPath($relativePath)) {
            return;
        }

        $absolute = public_path($relativePath);

        if (!is_file($absolute)) {
            return;
        }

        if (!File::delete($absolute)) {
            Log::warning('UploadEducatorDocumentsToS3Job: failed to delete temp file', [
                'user' => $this->userId,
                'path' => $relativePath,
            ]);

            return;
        }

        $directory = dirname($absolute);
        while ($this->isTempDirectory($directory)) {
            if (!@rmdir($directory)) {
                break;
            }

            $directory = dirname($directory);
        }
    }

    private function isTempDirectory(string $absoluteDirectory): bool
    {
        $tempRoot = realpath(public_path('temp/educators'));

        if ($tempRoot === false) {
            return false;
        }

        $directory = realpath($absoluteDirectory);

        return $directory !== false
            && $directory !== $tempRoot
            && Str::startsWith($directory, $tempRoot . DIRECTORY_SEPARATOR);
    }

    private function isTempPath(?string $path): bool
    {
        return $path !== null && Str::startsWith($path, self::TEMP_PREFIX);
    }
}
