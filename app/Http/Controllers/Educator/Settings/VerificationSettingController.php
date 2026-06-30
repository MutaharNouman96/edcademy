<?php

namespace App\Http\Controllers\Educator\Settings;

use App\Http\Controllers\Controller;
use App\Models\Educator\VerificationSetting;
use App\Models\EducatorAdditionalDocument;
use App\Models\EducatorProfile;
use App\Services\EducatorDocumentStorageService;
use Illuminate\Http\Request;

class VerificationSettingController extends Controller
{
    public function __construct(
        private EducatorDocumentStorageService $storage
    ) {
    }

    /**
     * Document type definitions with the educator's currently stored files.
     */
    public function documentTypes(Request $request)
    {
        $user = $request->user();
        $profile = EducatorProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['hourly_rate' => 0]
        );

        $types = [];
        foreach (EducatorProfile::verificationDocumentTypes() as $key => $definition) {
            $entry = [
                'type' => $key,
                'label' => $definition['label'],
                'icon' => $definition['icon'],
                'accept' => $definition['accept'],
                'max_kb' => $definition['max_kb'],
                'max_mb' => (int) ceil($definition['max_kb'] / 1024),
                'hint' => $definition['hint'],
                'multiple' => (bool) ($definition['multiple'] ?? false),
                'max_files' => $definition['max_files'] ?? 1,
                'document' => null,
                'documents' => [],
            ];

            if ($entry['multiple']) {
                $entry['documents'] = $user->additionalDocuments()
                    ->latest()
                    ->get()
                    ->map(fn (EducatorAdditionalDocument $doc) => $doc->toDocumentPayload())
                    ->values()
                    ->all();
            } elseif (!empty($definition['column'])) {
                $path = $profile->{$definition['column']} ?? null;
                $entry['document'] = EducatorProfile::documentPayload(
                    $path,
                    $definition['label'],
                    $definition['icon']
                );
            }

            $types[] = $entry;
        }

        return response()->json(['types' => $types]);
    }

    /**
     * Return a short-lived signed URL so private S3 objects can be previewed in the browser.
     */
    public function previewUrl(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $path = (string) $request->input('path');
        $user = $request->user();

        if (!$this->userOwnsDocumentPath($user->id, $path)) {
            return response()->json(['message' => 'Document not found.'], 403);
        }

        $minutes = 30;
        $url = $this->storage->temporaryUrl($path, $minutes);

        if (!$url) {
            return response()->json(['message' => 'Could not generate preview URL.'], 404);
        }

        return response()->json([
            'url' => $url,
            'expires_at' => now()->addMinutes($minutes)->toIso8601String(),
        ]);
    }

    /**
     * Upload a file to S3, replace any existing file for profile types, and persist the path.
     */
    public function uploadDocument(Request $request)
    {
        $type = (string) $request->input('type');
        $definition = EducatorProfile::verificationDocumentType($type);

        if (!$definition) {
            return response()->json(['message' => 'Invalid upload type.'], 422);
        }

        $fileRule = ['required', 'file', 'max:' . $definition['max_kb']];
        if (isset($definition['mimes'])) {
            $fileRule[] = 'mimes:' . $definition['mimes'];
        }
        if (isset($definition['mimetypes'])) {
            $fileRule[] = 'mimetypes:' . $definition['mimetypes'];
        }

        $request->validate(['file' => $fileRule]);

        $user = $request->user();
        $file = $request->file('file');
        $profile = EducatorProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['hourly_rate' => 0]
        );

        try {
            if (!empty($definition['multiple'])) {
                $count = $user->additionalDocuments()->count();
                $maxFiles = (int) ($definition['max_files'] ?? 10);
                if ($count >= $maxFiles) {
                    return response()->json([
                        'message' => "Maximum {$maxFiles} additional documents allowed.",
                    ], 422);
                }

                $path = $this->storage->uploadToS3($file, $user->id, $definition['folder']);
                $document = EducatorAdditionalDocument::create([
                    'educator_id' => $user->id,
                    'document_path' => $path,
                    'document_type' => $file->getClientMimeType(),
                    'document_name' => $file->getClientOriginalName(),
                    'document_size' => (string) $file->getSize(),
                ]);

                return response()->json([
                    'type' => $type,
                    'path' => $path,
                    'url' => $path,
                    'document' => $document->toDocumentPayload(),
                ]);
            }

            $column = $definition['column'];
            $previousPath = $profile->{$column};
            $path = $this->storage->replaceOnS3(
                $file,
                $user->id,
                $definition['folder'],
                $previousPath
            );

            $profile->{$column} = $path;
            $profile->save();

            return response()->json([
                'type' => $type,
                'path' => $path,
                'url' => $path,
                'document' => EducatorProfile::documentPayload(
                    $path,
                    $definition['label'],
                    $definition['icon']
                ),
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove a profile document by type (deletes from S3 and clears the DB field).
     */
    public function destroyProfileDocument(Request $request, string $type)
    {
        $definition = EducatorProfile::verificationDocumentType($type);

        if (!$definition || !empty($definition['multiple']) || empty($definition['column'])) {
            return response()->json(['message' => 'Invalid document type.'], 422);
        }

        $user = $request->user();
        $profile = EducatorProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            return response()->json(['message' => 'Document not found.'], 404);
        }

        $column = $definition['column'];
        $path = $profile->{$column};

        if (!$path) {
            return response()->json(['message' => 'Document not found.'], 404);
        }

        $this->storage->delete($path);
        $profile->{$column} = null;
        $profile->save();

        return response()->json([
            'status' => true,
            'message' => 'Document removed.',
            'type' => $type,
        ]);
    }

    /**
     * Remove an additional document (deletes from S3 and DB).
     */
    public function destroyDocument(Request $request, EducatorAdditionalDocument $document)
    {
        if ((int) $document->educator_id !== (int) $request->user()->id) {
            abort(403);
        }

        $this->storage->delete($document->document_path);
        $document->delete();

        return response()->json([
            'status' => true,
            'message' => 'Document removed.',
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'business_type' => 'required|in:individual,company',
            'tos' => 'accepted',
        ]);

        $profile = EducatorProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['hourly_rate' => 0]
        );

        $profile->consent_verified = true;
        $profile->save();

        VerificationSetting::updateOrCreate(
            ['educator_id' => $user->id],
            [
                'business_type' => $request->business_type,
                'tos' => true,
                'gov_id_file' => $profile->govt_id_path,
                'credential_file' => $profile->degree_proof_path,
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Verification submitted successfully.',
        ]);
    }

    private function userOwnsDocumentPath(int $userId, string $path): bool
    {
        if ($this->storage->isAllowedForUser($path, $userId)) {
            return true;
        }

        $profile = EducatorProfile::where('user_id', $userId)->first();
        if ($profile) {
            foreach (EducatorProfile::verificationDocumentTypes() as $definition) {
                if (empty($definition['column'])) {
                    continue;
                }

                if ($profile->{$definition['column']} === $path) {
                    return true;
                }
            }
        }

        return EducatorAdditionalDocument::where('educator_id', $userId)
            ->where('document_path', $path)
            ->exists();
    }
}
