<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LessonPublicAssetUploadController extends Controller
{
    /**
     * Direct upload to public/storage/lessons/{worksheets|materials}/ with a long random filename.
     * PDFs are watermarked (same as lesson store); other types are stored as-is.
     */
    public function store(Request $request, string $kind)
    {
        if (!in_array($kind, ['worksheets', 'materials'], true)) {
            abort(404);
        }

        $rules = $kind === 'worksheets'
            ? ['file' => 'required|file|mimes:pdf,doc,docx|max:10240']
            : ['file' => 'required|file|mimes:pdf,ppt,pptx|max:10240'];

        $validated = $request->validate($rules);
        $file = $validated['file'];

        $ext = strtolower($file->getClientOriginalExtension() ?: '');
        $allowed = $kind === 'worksheets' ? ['pdf', 'doc', 'docx'] : ['pdf', 'ppt', 'pptx'];
        if (!in_array($ext, $allowed, true)) {
            return response()->json(['message' => 'Invalid file type.'], 422);
        }

        $randomName = bin2hex(random_bytes(32)) . '.' . $ext;
        $subDir = $kind === 'worksheets' ? 'lessons/worksheets' : 'lessons/materials';
        $destinationFolder = public_path('storage/' . $subDir);

        if (!File::isDirectory($destinationFolder)) {
            File::makeDirectory($destinationFolder, 0755, true);
        }

        $fullPath = $destinationFolder . '/' . $randomName;

        if ($ext === 'pdf') {
            $docService = new DocumentService();
            File::put($fullPath, $docService->generateWatermarkedPdf($file->getRealPath()));
        } else {
            $file->move($destinationFolder, $randomName);
        }

        $relative = $subDir . '/' . $randomName;
        $url = asset('storage/' . $relative);

        return response()->json([
            'path' => $relative,
            'url' => $url,
        ]);
    }
}
