<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TempVideoUploadController extends Controller
{
    private const MAX_BYTES = 2147483648; // 2 GB

    private const ALLOWED_EXTENSIONS = ['mp4', 'mov', 'avi', 'wmv', 'webm', 'mkv'];

    /**
     * Final S3 folder for lesson videos. Videos are pushed straight here on
     * upload — we no longer keep a local "temp_upload" copy nor a deferred
     * (video_temp_path / Vimeo) processing step.
     */
    private const S3_VIDEO_DIR = 'lessons/videos';

    /**
     * Local scratch folder used ONLY to reassemble Dropzone chunks before the
     * file is streamed to S3. Cleaned up immediately after the S3 upload.
     */
    private const CHUNK_DIR = 'temp_upload/chunks';

    /**
     * Single-file or Dropzone chunked upload. In both cases the resulting video
     * is uploaded directly to S3 and the response returns the S3 object key as
     * "path" (plus a temporary preview "url").
     */
    public function store(Request $request)
    {
        try {
            $file = $request->file('file');
            if (!$file || !$file->isValid()) {
                return $this->errorResponse('No valid file uploaded', [
                    'upload_error' => $file ? $file->getErrorMessage() : 'file missing from request',
                    'upload_error_code' => $file ? (string) $file->getError() : 'n/a',
                    'original_name' => $file?->getClientOriginalName() ?? 'n/a',
                ]);
            }

            $ext = strtolower($file->getClientOriginalExtension() ?: '');
            if (!in_array($ext, self::ALLOWED_EXTENSIONS, true)) {
                return $this->errorResponse('Invalid video type', [
                    'received_extension' => $ext ?: 'none',
                    'allowed_extensions' => implode(', ', self::ALLOWED_EXTENSIONS),
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType() ?: 'unknown',
                ]);
            }

            $dzUuid = $request->input('dzuuid');
            $chunkIndex = $request->input('dzchunkindex');
            $totalChunks = $request->input('dztotalchunkcount');

            // Chunked upload path (handled per-chunk; final chunk assembles + uploads to S3).
            if ($dzUuid !== null && $chunkIndex !== null && $totalChunks !== null) {
                return $this->storeChunk($request, $file, (string) $dzUuid, (int) $chunkIndex, (int) $totalChunks);
            }

            $size = $file->getSize();
            if ($size > self::MAX_BYTES) {
                return $this->errorResponse('File exceeds 2 GB limit', [
                    'file_size_bytes' => (string) $size,
                    'max_size_bytes' => (string) self::MAX_BYTES,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }

            // Single-shot upload: stream the uploaded temp file straight to S3.
            $realPath = $file->getRealPath();
            $stream = fopen($realPath, 'rb');
            if ($stream === false) {
                return $this->errorResponse('Could not open uploaded file for reading', [
                    'temp_path' => $realPath ?: 'n/a',
                    'original_name' => $file->getClientOriginalName(),
                    'php_error' => $this->lastPhpError(),
                ], 500);
            }

            try {
                $payload = $this->uploadStreamToS3($stream, $ext, [
                    'upload_mode' => 'single',
                    'original_name' => $file->getClientOriginalName(),
                    'file_size_bytes' => (string) $size,
                ]);
            } finally {
                if (is_resource($stream)) {
                    fclose($stream);
                }
            }

            return response()->json($payload);
        } catch (\Throwable $e) {
            return $this->respondWithException($e, 'store video upload');
        }
    }

    private function storeChunk(Request $request, $file, string $dzUuid, int $chunkIndex, int $totalChunks): \Illuminate\Http\JsonResponse
    {
        try {
            if ($totalChunks < 1 || $chunkIndex < 0 || $chunkIndex >= $totalChunks) {
                return $this->errorResponse('Invalid chunk parameters', [
                    'dzuuid' => $dzUuid,
                    'chunk_index' => (string) $chunkIndex,
                    'total_chunks' => (string) $totalChunks,
                ]);
            }

            $chunkDirRelative = self::CHUNK_DIR . '/' . $dzUuid;
            $chunkDirAbs = public_path('storage/' . $chunkDirRelative);
            if (!File::isDirectory($chunkDirAbs)) {
                if (!File::makeDirectory($chunkDirAbs, 0755, true)) {
                    return $this->errorResponse('Could not create chunk directory', [
                        'chunk_dir' => $chunkDirAbs,
                        'php_error' => $this->lastPhpError(),
                    ], 500);
                }
            }

            $chunkFilePath = $chunkDirAbs . '/' . $chunkIndex;
            if (File::put($chunkFilePath, file_get_contents($file->getRealPath())) === false) {
                return $this->errorResponse('Could not save uploaded chunk', [
                    'chunk_dir' => $chunkDirAbs,
                    'chunk_index' => (string) $chunkIndex,
                    'chunk_file' => $chunkFilePath,
                    'php_error' => $this->lastPhpError(),
                ], 500);
            }

            // Not the last chunk yet — just acknowledge receipt.
            if ($chunkIndex !== $totalChunks - 1) {
                return response()->json(['status' => 'chunk_received']);
            }

            $totalSize = 0;
            for ($i = 0; $i < $totalChunks; $i++) {
                $p = $chunkDirAbs . '/' . $i;
                if (!is_file($p)) {
                    File::deleteDirectory($chunkDirAbs);

                    return $this->errorResponse('Missing chunk during reassembly', [
                        'missing_chunk_index' => (string) $i,
                        'total_chunks' => (string) $totalChunks,
                        'dzuuid' => $dzUuid,
                        'chunk_dir' => $chunkDirAbs,
                    ]);
                }
                $totalSize += filesize($p);
            }

            if ($totalSize > self::MAX_BYTES) {
                File::deleteDirectory($chunkDirAbs);

                return $this->errorResponse('Assembled file exceeds 2 GB limit', [
                    'assembled_size_bytes' => (string) $totalSize,
                    'max_size_bytes' => (string) self::MAX_BYTES,
                    'total_chunks' => (string) $totalChunks,
                    'dzuuid' => $dzUuid,
                ]);
            }

            $ext = strtolower($file->getClientOriginalExtension() ?: '');
            if (!in_array($ext, self::ALLOWED_EXTENSIONS, true)) {
                File::deleteDirectory($chunkDirAbs);

                return $this->errorResponse('Invalid video type on final chunk', [
                    'received_extension' => $ext ?: 'none',
                    'allowed_extensions' => implode(', ', self::ALLOWED_EXTENSIONS),
                    'original_name' => $file->getClientOriginalName(),
                    'dzuuid' => $dzUuid,
                ]);
            }

            // Reassemble all chunks into a single local scratch file.
            $assembledFull = public_path('storage/' . self::CHUNK_DIR . '/' . $dzUuid . '.' . $ext);
            $out = fopen($assembledFull, 'wb');
            if ($out === false) {
                File::deleteDirectory($chunkDirAbs);

                return $this->errorResponse('Could not create assembled output file', [
                    'assembled_file' => $assembledFull,
                    'total_chunks' => (string) $totalChunks,
                    'dzuuid' => $dzUuid,
                    'php_error' => $this->lastPhpError(),
                ], 500);
            }

            try {
                for ($i = 0; $i < $totalChunks; $i++) {
                    $chunkPath = $chunkDirAbs . '/' . $i;
                    $in = fopen($chunkPath, 'rb');
                    if ($in === false) {
                        @unlink($assembledFull);
                        File::deleteDirectory($chunkDirAbs);

                        return $this->errorResponse('Could not read chunk during reassembly', [
                            'chunk_index' => (string) $i,
                            'chunk_file' => $chunkPath,
                            'assembled_file' => $assembledFull,
                            'dzuuid' => $dzUuid,
                            'php_error' => $this->lastPhpError(),
                        ], 500);
                    }

                    $copied = stream_copy_to_stream($in, $out);
                    fclose($in);

                    if ($copied === false) {
                        @unlink($assembledFull);
                        File::deleteDirectory($chunkDirAbs);

                        return $this->errorResponse('Could not copy chunk into assembled file', [
                            'chunk_index' => (string) $i,
                            'chunk_file' => $chunkPath,
                            'assembled_file' => $assembledFull,
                            'dzuuid' => $dzUuid,
                            'php_error' => $this->lastPhpError(),
                        ], 500);
                    }
                }
            } finally {
                fclose($out);
            }

            // Per-chunk pieces are no longer needed.
            File::deleteDirectory($chunkDirAbs);

            // Stream the assembled file to S3, then drop the local scratch copy.
            $stream = fopen($assembledFull, 'rb');
            if ($stream === false) {
                @unlink($assembledFull);

                return $this->errorResponse('Could not open assembled file for S3 upload', [
                    'assembled_file' => $assembledFull,
                    'assembled_size_bytes' => (string) $totalSize,
                    'dzuuid' => $dzUuid,
                    'php_error' => $this->lastPhpError(),
                ], 500);
            }

            try {
                $payload = $this->uploadStreamToS3($stream, $ext, [
                    'upload_mode' => 'chunked',
                    'dzuuid' => $dzUuid,
                    'total_chunks' => (string) $totalChunks,
                    'assembled_file' => $assembledFull,
                    'assembled_size_bytes' => (string) $totalSize,
                ]);
            } finally {
                if (is_resource($stream)) {
                    fclose($stream);
                }
                @unlink($assembledFull);
            }

            return response()->json($payload);
        } catch (\Throwable $e) {
            return $this->respondWithException($e, 'store chunked video upload', [
                'dzuuid' => $dzUuid,
                'chunk_index' => (string) $chunkIndex,
                'total_chunks' => (string) $totalChunks,
            ]);
        }
    }

    /**
     * Push an open file stream to S3 under the lesson videos directory.
     *
     * @return array{path: string, url: string}
     *
     * @throws \RuntimeException
     */
    private function uploadStreamToS3($stream, string $ext, array $context = []): array
    {
        if (!is_resource($stream)) {
            throw new \RuntimeException('S3 upload aborted: file stream is not a valid resource');
        }

        $key = self::S3_VIDEO_DIR . '/' . Str::uuid() . '.' . $ext;
        $uploaded = Storage::disk('s3')->put($key, $stream);
        $existsOnS3 = Storage::disk('s3')->exists($key);

        if (!$uploaded || !$existsOnS3) {
            throw new \RuntimeException($this->formatFailureMessage('Video upload to S3 failed', array_merge($context, [
                's3_key' => $key,
                'extension' => $ext,
                'put_returned' => $uploaded ? 'true' : 'false',
                'exists_on_s3' => $existsOnS3 ? 'true' : 'false',
                'php_error' => $this->lastPhpError(),
            ])));
        }

        return $this->responsePayload($key);
    }

    /**
     * The "path" is the S3 object key persisted on the lesson (video_storage_path),
     * "url" is a short-lived signed URL purely for the in-form preview.
     *
     * @return array{path: string, url: string}
     *
     * @throws \RuntimeException
     */
    private function responsePayload(string $key): array
    {
        try {
            $url = Storage::disk('s3')->temporaryUrl($key, now()->addMinutes(30));
        } catch (\Throwable $e) {
            throw new \RuntimeException($this->formatFailureMessage('Video uploaded to S3 but preview URL generation failed', [
                's3_key' => $key,
                'exception' => $e::class,
                'detail' => $e->getMessage(),
            ]));
        }

        return [
            'path' => $key,
            'url' => $url,
        ];
    }

    private function errorResponse(string $message, array $context = [], int $status = 422): \Illuminate\Http\JsonResponse
    {
        $fullMessage = $this->formatFailureMessage($message, $context);

        Log::warning('[TempVideoUploadController] ' . $message, $context);

        return response()->json(['message' => $fullMessage], $status);
    }

    private function respondWithException(\Throwable $e, string $operation, array $context = [], int $status = 500): \Illuminate\Http\JsonResponse
    {
        Log::error("[TempVideoUploadController] {$operation} failed", array_merge($context, [
            'exception' => $e::class,
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]));

        $message = "[{$operation}] {$e->getMessage()}";

        if ($e->getPrevious()) {
            $message .= ' (caused by: ' . $e->getPrevious()->getMessage() . ')';
        }

        if (!empty($context)) {
            $message .= ' Context: ' . collect($context)
                ->map(fn ($value, $key) => "{$key}=" . (is_scalar($value) ? $value : json_encode($value)))
                ->implode(', ');
        }

        return response()->json(['message' => $message], $status);
    }

    private function formatFailureMessage(string $message, array $context = []): string
    {
        if (empty($context)) {
            return $message;
        }

        $details = collect($context)
            ->map(fn ($value, $key) => "{$key}=" . (is_scalar($value) ? $value : json_encode($value)))
            ->implode('; ');

        return "{$message} ({$details})";
    }

    private function lastPhpError(): string
    {
        $error = error_get_last();

        return ($error && isset($error['message'])) ? $error['message'] : 'none';
    }
}
