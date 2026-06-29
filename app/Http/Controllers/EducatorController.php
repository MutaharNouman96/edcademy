<?php

namespace App\Http\Controllers;

use App\Jobs\SendEducatorWelcomeEmailJob;
use App\Jobs\UploadEducatorDocumentsToS3Job;
use App\Models\User;
use App\Models\EducatorProfile;
use App\Models\EducatorAdditionalDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


class EducatorController extends Controller
{
    /**
     * Root (relative to /public) where Dropzone parks files before the form is
     * submitted. The deferred S3 job later reads from here and cleans up.
     */
    private const TEMP_ROOT = 'temp/educators';

    /**
     * Per-field upload rules used by both the async temp-upload endpoint and the
     * final form submission. Sizes are in kilobytes.
     */
    private const UPLOAD_RULES = [
        'cv' => ['mimes' => 'jpeg,png,jpg,pdf', 'max' => 5120],
        'degree_proof' => ['mimes' => 'jpeg,png,jpg,pdf', 'max' => 5120],
        'additional_document' => ['mimes' => 'jpeg,png,jpg,gif,webp,pdf', 'max' => 5120],
        'intro_video' => ['mimetypes' => 'video/mp4,video/quicktime', 'max' => 51200],
    ];

    public function create()
    {
        return view('educator.become_tutor');
    }

    /**
     * Step: receive a single Dropzone file and stash it under public/temp.
     * Returns the relative temp path that the form keeps in a hidden input.
     */
    public function tempUpload(Request $request)
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

        $file = $request->file('file');
        $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'dat');
        $safeName = Str::uuid() . '_' . (Str::slug($original) ?: 'file') . '.' . $extension;

        $relativeDir = self::TEMP_ROOT . '/' . $type;
        $file->move(public_path($relativeDir), $safeName);

        return response()->json([
            'path' => $relativeDir . '/' . $safeName,
            'original_name' => $file->getClientOriginalName() !== ''
                ? $file->getClientOriginalName()
                : $safeName,
        ]);
    }

    /**
     * Step: remove a previously stashed temp file when the user deletes it from
     * a Dropzone before submitting.
     */
    public function tempUploadDelete(Request $request)
    {
        $path = (string) $request->input('path');

        if (!$this->isValidTempPath($path)) {
            return response()->json(['message' => 'Invalid path.'], 422);
        }

        $absolute = public_path($path);
        if (is_file($absolute)) {
            @unlink($absolute);
        }

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $request->validate([
            // Step 1
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',

            // Step 2
            'primary_subject' => 'required|array|min:1',
            'primary_subject.*' => 'string|in:Mathematics,Science,English,Computer Science,Languages,Other',
            'teaching_levels' => 'required|array',
            'hourly_rate' => 'required|numeric|min:5',
            'certifications' => 'nullable|string',
            'preferred_teaching_style' => 'nullable|string|max:255',

            // Step 3 — Dropzone already parked the files in public/temp, so we
            // only receive their relative temp paths here.
            'cv_temp' => 'required|string',
            'degree_proof_temp' => 'nullable|string',
            'intro_video_temp' => 'nullable|string',
            'additional_documents_temp' => 'nullable|array|max:10',
            'additional_documents_temp.*' => 'string',
            'consent' => 'accepted',
        ]);

        // Keep only temp references that point at real files inside public/temp.
        $cvTemp = $this->resolveTempPath($request->input('cv_temp'));
        $degreeTemp = $this->resolveTempPath($request->input('degree_proof_temp'));
        $videoTemp = $this->resolveTempPath($request->input('intro_video_temp'));
        $additionalTemps = collect($request->input('additional_documents_temp', []))
            ->map(fn ($p) => $this->resolveTempPath($p))
            ->filter()
            ->values();

        if (!$cvTemp) {
            return back()
                ->with('error', 'Your CV upload could not be found. Please upload it again.')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'educator',
            ]);

            try {
                $user->sendEmailVerificationNotification();
            } catch (\Exception $e) {
                Log::error('Failed to send email verification notification: ' . $e->getMessage());
            }

            // Store the temp paths for now; the deferred S3 job swaps these for
            // the permanent S3 URLs a minute after submission.
            EducatorProfile::create([
                'user_id' => $user->id,
                'primary_subject' => implode(', ', $request->primary_subject),
                'teaching_levels' => json_encode($request->teaching_levels),
                'hourly_rate' => $request->hourly_rate,
                'certifications' => $request->certifications,
                'preferred_teaching_style' => json_encode($request->preferred_teaching_style),
                'cv_path' => $cvTemp,
                'degree_proof_path' => $degreeTemp,
                'intro_video_path' => $videoTemp,
                'consent_verified' => true,
                'status' => 'pending',
            ]);

            foreach ($additionalTemps as $tempPath) {
                $absolute = public_path($tempPath);
                EducatorAdditionalDocument::create([
                    'educator_id' => $user->id,
                    'document_path' => $tempPath,
                    'document_type' => is_file($absolute) ? File::mimeType($absolute) : null,
                    'document_name' => $this->originalNameFromTemp($tempPath),
                    'document_size' => is_file($absolute) ? File::size($absolute) : null,
                ]);
            }

            DB::commit();

            // Step: defer the heavy S3 upload by one minute, and queue the
            // welcome email separately.
            UploadEducatorDocumentsToS3Job::dispatch($user->id)->delay(now()->addMinute());
            SendEducatorWelcomeEmailJob::dispatch($user->id);

            Session::flash('success', 'Your application has been submitted successfully!');
            auth()->login($user);

            // Educators must verify their email first; once verified they are
            // routed to the mandatory Stripe payout setup screen.
            return redirect()->route('verification.notice')
                ->with('success', 'Your application has been submitted! Please verify your email to continue, then set up your payouts.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'An error occurred while submitting.' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Validate that a temp path is safe and resolves to an existing file inside
     * public/temp/educators. Returns the normalized relative path or null.
     */
    private function resolveTempPath(?string $path): ?string
    {
        if (!$this->isValidTempPath($path)) {
            return null;
        }

        return is_file(public_path($path)) ? $path : null;
    }

    /**
     * Guard against path traversal: only allow paths under the temp root.
     */
    private function isValidTempPath(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        if (Str::contains($path, ['..', "\0"])) {
            return false;
        }

        return Str::startsWith($path, self::TEMP_ROOT . '/');
    }

    /**
     * Temp files are named "{uuid}_{slug}.{ext}". Recover a readable name.
     */
    private function originalNameFromTemp(string $tempPath): string
    {
        $basename = basename($tempPath);
        $pos = strpos($basename, '_');

        return $pos !== false ? substr($basename, $pos + 1) : $basename;
    }
}
