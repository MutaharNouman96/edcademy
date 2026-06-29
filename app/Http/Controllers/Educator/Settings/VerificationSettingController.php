<?php

namespace App\Http\Controllers\Educator\Settings;

use App\Http\Controllers\Controller;
use App\Models\Educator\VerificationSetting;
use App\Models\EducatorAdditionalDocument;
use App\Models\EducatorProfile;
use App\Services\EducatorDocumentStorageService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VerificationSettingController extends Controller
{
    private const UPLOAD_RULES = [
        'gov_id' => [
            'folder' => 'gov_ids',
            'mimes' => 'jpeg,png,jpg,gif,webp,pdf',
            'max' => 5120,
        ],
        'degree_proof' => [
            'folder' => 'degrees',
            'mimes' => 'jpeg,png,jpg,gif,webp,pdf',
            'max' => 5120,
        ],
        'intro_video' => [
            'folder' => 'videos',
            'mimetypes' => 'video/mp4,video/quicktime',
            'max' => 51200,
        ],
        'additional_document' => [
            'folder' => 'additional_documents',
            'mimes' => 'jpeg,png,jpg,gif,webp,pdf',
            'max' => 5120,
        ],
    ];

    public function __construct(
        private EducatorDocumentStorageService $storage
    ) {
    }

    /**
     * Dropzone endpoint — streams the file straight to S3 and returns the URL.
     */
    public function uploadDocument(Request $request)
    {
        $type = (string) $request->input('type');

        if (!isset(self::UPLOAD_RULES[$type])) {
            return response()->json(['message' => 'Invalid upload type.'], 422);
        }

        $rule = self::UPLOAD_RULES[$type];
        $fileRule = ['required', 'file', 'max:' . $rule['max']];
        if (isset($rule['mimes'])) {
            $fileRule[] = 'mimes:' . $rule['mimes'];
        }
        if (isset($rule['mimetypes'])) {
            $fileRule[] = 'mimetypes:' . $rule['mimetypes'];
        }

        $request->validate(['file' => $fileRule]);

        $user = $request->user();
        $file = $request->file('file');

        try {
            $url = $this->storage->uploadToS3($file, $user->id, $rule['folder']);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json([
            'path' => $url,
            'url' => $url,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => (string) $file->getSize(),
        ]);
    }

    /**
     * Dropzone removedfile — delete the S3 object the user dropped before submitting.
     */
    public function deleteUpload(Request $request)
    {
        $path = (string) $request->input('path');
        $user = $request->user();

        if (!$this->storage->isAllowedForUser($path, $user->id)) {
            return response()->json(['message' => 'Invalid path.'], 422);
        }

        $this->storage->delete($path);

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'gov_id_path' => 'nullable|url',
            'degree_proof_path' => 'nullable|url',
            'intro_video_path' => 'nullable|url',
            'additional_documents_new' => 'nullable|array|max:10',
            'additional_documents_new.*.path' => 'required|url',
            'additional_documents_new.*.name' => 'required|string|max:255',
            'additional_documents_new.*.type' => 'nullable|string|max:100',
            'additional_documents_new.*.size' => 'nullable|string|max:50',
            'business_type' => 'required|in:individual,company',
            'tos' => 'accepted',
        ]);

        $profile = EducatorProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['hourly_rate' => 0]
        );

        $this->assignProfilePath($profile, 'govt_id_path', $request->input('gov_id_path'), $user->id);
        $this->assignProfilePath($profile, 'degree_proof_path', $request->input('degree_proof_path'), $user->id);
        $this->assignProfilePath($profile, 'intro_video_path', $request->input('intro_video_path'), $user->id);

        $profile->consent_verified = true;
        $profile->save();

        foreach ($request->input('additional_documents_new', []) as $doc) {
            $path = $doc['path'] ?? null;
            if (!$this->storage->isAllowedForUser($path, $user->id)) {
                continue;
            }

            EducatorAdditionalDocument::create([
                'educator_id' => $user->id,
                'document_path' => $path,
                'document_type' => $doc['type'] ?? null,
                'document_name' => $doc['name'],
                'document_size' => $doc['size'] ?? null,
            ]);
        }

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

    /**
     * Replace a profile document path, deleting the previous S3/local file first.
     */
    private function assignProfilePath(EducatorProfile $profile, string $column, ?string $newUrl, int $userId): void
    {
        if (!$newUrl || !$this->storage->isAllowedForUser($newUrl, $userId)) {
            return;
        }

        $old = $profile->{$column};
        if ($old && $old !== $newUrl) {
            $this->storage->delete($old);
        }

        $profile->{$column} = $newUrl;
    }
}
