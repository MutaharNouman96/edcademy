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
     * Single-file or Dropzone chunked upload into storage/app/temp_upload.
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

        if ($dzUuid !== null && $chunkIndex !== null && $totalChunks !== null) {
            return $this->storeChunk($request, $file, (string) $dzUuid, (int) $chunkIndex, (int) $totalChunks);
        }

        $size = $file->getSize();
        if ($size > self::MAX_BYTES) {
            return response()->json(['message' => 'File exceeds 2 GB limit.'], 422);
        }

        $originalName = $file->getClientOriginalName();
        $safeBase = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) ?: 'video';
        $relative = 'temp_upload/' . Str::uuid() . '_' . $safeBase . '.' . $ext;

        Storage::disk('local')->put($relative, file_get_contents($file->getRealPath()));

        return response()->json($this->responsePayload($relative));
    }

    private function storeChunk(Request $request, $file, string $dzUuid, int $chunkIndex, int $totalChunks): \Illuminate\Http\JsonResponse
    {
        if ($totalChunks < 1 || $chunkIndex < 0 || $chunkIndex >= $totalChunks) {
            return response()->json(['message' => 'Invalid chunk parameters.'], 422);
        }

        $chunkDir = 'temp_upload/chunks/' . $dzUuid;
        Storage::disk('local')->put(
            $chunkDir . '/' . $chunkIndex,
            file_get_contents($file->getRealPath())
        );

        if ($chunkIndex !== $totalChunks - 1) {
            return response()->json(['status' => 'chunk_received']);
        }

        $totalSize = 0;
        for ($i = 0; $i < $totalChunks; $i++) {
            $p = storage_path('app/' . $chunkDir . '/' . $i);
            if (!is_file($p)) {
                Storage::disk('local')->deleteDirectory($chunkDir);

                return response()->json(['message' => 'Missing chunk ' . $i], 422);
            }
            $totalSize += filesize($p);
        }

        if ($totalSize > self::MAX_BYTES) {
            Storage::disk('local')->deleteDirectory($chunkDir);

            return response()->json(['message' => 'File exceeds 2 GB limit.'], 422);
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: '');
        if (!in_array($ext, self::ALLOWED_EXTENSIONS, true)) {
            Storage::disk('local')->deleteDirectory($chunkDir);

            return response()->json(['message' => 'Invalid video type.'], 422);
        }

        $originalName = $file->getClientOriginalName();
        $safeBase = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) ?: 'video';
        $relative = 'temp_upload/' . Str::uuid() . '_' . $safeBase . '.' . $ext;
        $finalFull = storage_path('app/' . $relative);

        $dir = dirname($finalFull);
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $out = fopen($finalFull, 'wb');
        if ($out === false) {
            Storage::disk('local')->deleteDirectory($chunkDir);

            return response()->json(['message' => 'Could not create output file.'], 500);
        }

        try {
            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkPath = storage_path('app/' . $chunkDir . '/' . $i);
                $in = fopen($chunkPath, 'rb');
                if ($in === false) {
                    fclose($out);
                    @unlink($finalFull);
                    Storage::disk('local')->deleteDirectory($chunkDir);

                    return response()->json(['message' => 'Could not read chunk ' . $i], 500);
                }
                stream_copy_to_stream($in, $out);
                fclose($in);
            }
        } finally {
            fclose($out);
        }

        Storage::disk('local')->deleteDirectory($chunkDir);

        return response()->json($this->responsePayload($relative));
    }

    /**
     * @return array{path: string, full_path: string, url: string}
     */
    private function responsePayload(string $relative): array
    {
        $full = storage_path('app/' . $relative);

        return [
            'path' => $relative,
            'full_path' => $full,
        ];
    }
}
