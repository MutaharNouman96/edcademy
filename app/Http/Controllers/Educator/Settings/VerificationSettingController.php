<?php

namespace App\Http\Controllers\Educator\Settings;

use App\Http\Controllers\Controller;
use App\Models\Educator\VerificationSetting;
use App\Models\EducatorAdditionalDocument;
use App\Models\EducatorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class VerificationSettingController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'gov_id_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,pdf|max:6144',
            'credential_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,pdf|max:6144',
            'business_type' => 'required|in:individual,company',
            'tos' => 'accepted',
            'additional_documents' => 'nullable|array|max:10',
            'additional_documents.*' => 'file|mimes:jpeg,png,jpg,gif,webp,pdf|max:6144',
        ]);

        $profile = EducatorProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['hourly_rate' => 0]
        );

        $this->ensurePublicDirs();

        if ($request->hasFile('gov_id_file')) {
            $file = $request->file('gov_id_file');
            $name = time() . '_' . $user->id . '_gov_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            if ($profile->govt_id_path && File::exists(public_path($profile->govt_id_path))) {
                File::delete(public_path($profile->govt_id_path));
            }
            $file->move(public_path('storage/educators/gov_ids'), $name);
            $profile->govt_id_path = 'storage/educators/gov_ids/' . $name;
        }

        if ($request->hasFile('credential_file')) {
            $file = $request->file('credential_file');
            $name = time() . '_' . $user->id . '_cred_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            if ($profile->degree_proof_path && File::exists(public_path($profile->degree_proof_path))) {
                File::delete(public_path($profile->degree_proof_path));
            }
            $file->move(public_path('storage/educators/degrees'), $name);
            $profile->degree_proof_path = 'storage/educators/degrees/' . $name;
        }

        $profile->consent_verified = true;
        $profile->save();

        if ($request->hasFile('additional_documents')) {
            foreach ($request->file('additional_documents') as $file) {
                if (! $file || ! $file->isValid()) {
                    continue;
                }
                $safeName = $user->id . '_' . uniqid('', true) . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
                $file->move(public_path('storage/educators/additional_documents'), $safeName);
                $relativePath = 'storage/educators/additional_documents/' . $safeName;
                EducatorAdditionalDocument::create([
                    'educator_id' => $user->id,
                    'document_path' => $relativePath,
                    'document_type' => $file->getClientMimeType(),
                    'document_name' => $file->getClientOriginalName(),
                    'document_size' => (string) $file->getSize(),
                ]);
            }
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

        if ($document->document_path && File::exists(public_path($document->document_path))) {
            File::delete(public_path($document->document_path));
        }

        $document->delete();

        return response()->json([
            'status' => true,
            'message' => 'Document removed.',
        ]);
    }

    private function ensurePublicDirs(): void
    {
        foreach ([
            public_path('storage/educators/gov_ids'),
            public_path('storage/educators/degrees'),
            public_path('storage/educators/additional_documents'),
        ] as $dir) {
            if (! File::isDirectory($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
        }
    }
}
