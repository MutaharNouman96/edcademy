<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
        $file = $request->file('file');
        if (!$file || !$file->isValid()) {
            return response()->json(['message' => 'No valid file uploaded.'], 422);
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: '');
        if (!in_array($ext, self::ALLOWED_EXTENSIONS, true)) {
            return response()->json(['message' => 'Invalid video type. Allowed: ' . implode(', ', self::ALLOWED_EXTENSIONS)], 422);
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
            return response()->json(['message' => 'File exceeds 2 GB limit.'], 422);
        }

        // Single-shot upload: stream the uploaded temp file straight to S3.
        $stream = fopen($file->getRealPath(), 'rb');

        try {
            $payload = $this->uploadStreamToS3($stream, $ext);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        if ($payload === null) {
            return response()->json(['message' => 'Video upload to storage failed. Please try again.'], 500);
        }

        return response()->json($payload);
    }

    private function storeChunk(Request $request, $file, string $dzUuid, int $chunkIndex, int $totalChunks): \Illuminate\Http\JsonResponse
    {
        if ($totalChunks < 1 || $chunkIndex < 0 || $chunkIndex >= $totalChunks) {
            return response()->json(['message' => 'Invalid chunk parameters.'], 422);
        }

        $chunkDirRelative = self::CHUNK_DIR . '/' . $dzUuid;
        $chunkDirAbs = public_path('storage/' . $chunkDirRelative);
        if (!File::isDirectory($chunkDirAbs)) {
            File::makeDirectory($chunkDirAbs, 0755, true);
        }

        File::put(
            $chunkDirAbs . '/' . $chunkIndex,
            file_get_contents($file->getRealPath())
        );

        // Not the last chunk yet — just acknowledge receipt.
        if ($chunkIndex !== $totalChunks - 1) {
            return response()->json(['status' => 'chunk_received']);
        }

        $totalSize = 0;
        for ($i = 0; $i < $totalChunks; $i++) {
            $p = $chunkDirAbs . '/' . $i;
            if (!is_file($p)) {
                File::deleteDirectory($chunkDirAbs);

                return response()->json(['message' => 'Missing chunk ' . $i], 422);
            }
            $totalSize += filesize($p);
        }

        if ($totalSize > self::MAX_BYTES) {
            File::deleteDirectory($chunkDirAbs);

            return response()->json(['message' => 'File exceeds 2 GB limit.'], 422);
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: '');
        if (!in_array($ext, self::ALLOWED_EXTENSIONS, true)) {
            File::deleteDirectory($chunkDirAbs);

            return response()->json(['message' => 'Invalid video type.'], 422);
        }

        // Reassemble all chunks into a single local scratch file.
        $assembledFull = public_path('storage/' . self::CHUNK_DIR . '/' . $dzUuid . '.' . $ext);
        $out = fopen($assembledFull, 'wb');
        if ($out === false) {
            File::deleteDirectory($chunkDirAbs);

            return response()->json(['message' => 'Could not create output file.'], 500);
        }

        try {
            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkPath = $chunkDirAbs . '/' . $i;
                $in = fopen($chunkPath, 'rb');
                if ($in === false) {
                    fclose($out);
                    @unlink($assembledFull);
                    File::deleteDirectory($chunkDirAbs);

                    return response()->json(['message' => 'Could not read chunk ' . $i], 500);
                }
                stream_copy_to_stream($in, $out);
                fclose($in);
            }
        } finally {
            fclose($out);
        }

        // Per-chunk pieces are no longer needed.
        File::deleteDirectory($chunkDirAbs);

        // Stream the assembled file to S3, then drop the local scratch copy.
        $stream = fopen($assembledFull, 'rb');

        try {
            $payload = $this->uploadStreamToS3($stream, $ext);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
            @unlink($assembledFull);
        }

        if ($payload === null) {
            return response()->json(['message' => 'Video upload to storage failed. Please try again.'], 500);
        }

        return response()->json($payload);
    }

    /**
     * Push an open file stream to S3 under the lesson videos directory.
     *
     * @return array{path: string, url: string}|null  null on failure
     */
    private function uploadStreamToS3($stream, string $ext): ?array
    {
        if (!is_resource($stream)) {
            return null;
        }

        $key = self::S3_VIDEO_DIR . '/' . Str::uuid() . '.' . $ext;

        $uploaded = Storage::disk('s3')->put($key, $stream);

        if (!$uploaded || !Storage::disk('s3')->exists($key)) {
            return null;
        }

        return $this->responsePayload($key);
    }

    /**
     * The "path" is the S3 object key persisted on the lesson (video_storage_path),
     * "url" is a short-lived signed URL purely for the in-form preview.
     *
     * @return array{path: string, url: string}
     */
    private function responsePayload(string $key): array
    {
        return [
            'path' => $key,
            'url' => Storage::disk('s3')->temporaryUrl($key, now()->addMinutes(30)),
        ];
    }
}
