<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EducatorProfile;
use App\Models\EducatorAdditionalDocument;
use App\Models\Payment;
use App\Models\Earning;
use App\Models\SessionCall;
use App\Mail\EducatorCreatedMail;
use App\Mail\EducatorNoteMail;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EducatorController extends Controller
{
    // ───────────────────────────────────────
    //  Show educator profile
    // ───────────────────────────────────────
    public function show($id)
    {
        $educator = User::where('role', 'educator')
            ->with(['educatorProfile', 'courses', 'additionalDocuments'])
            ->findOrFail($id);

        return view('admin.educator.show', compact('educator'));
    }

    // ───────────────────────────────────────
    //  Create educator form
    // ───────────────────────────────────────
    public function create()
    {
        return view('admin.educator.create');
    }

    // ───────────────────────────────────────
    //  Store new educator
    // ───────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'first_name'               => 'required|string|max:255',
            'last_name'                => 'required|string|max:255',
            'email'                    => 'required|email|unique:users,email',
            'primary_subject'          => 'required|string|max:255',
            'educator_type'            => 'nullable|in:teacher,tutor',
            'teaching_levels'          => 'required|array',
            'hourly_rate'              => 'required|numeric|min:5',
            'certifications'           => 'nullable|string',
            'preferred_teaching_style' => 'nullable|string|max:255',
            'cv' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,pdf|max:6000',
            'degree_proof'             => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,pdf|max:6000',
            'intro_video'              => 'nullable|file|mimetypes:video/mp4,video/quicktime|max:51200',
            'additional_documents'     => 'nullable|array|max:5',
            'additional_documents.*'    => 'file|mimes:jpeg,png,jpg,gif,webp,pdf|max:6000',
            'status'                   => 'required|in:pending,approved,rejected',
            'commission_rate'          => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Generate a random password
            $plainPassword = Str::random(10);

            // Create user
            $user = User::create([
                'first_name'      => $request->first_name,
                'last_name'       => $request->last_name,
                'email'           => $request->email,
                'password'        => Hash::make($plainPassword),
                'role'            => 'educator',
                'commission_rate' => $request->filled('commission_rate')
                    ? $request->commission_rate
                    : User::DEFAULT_COMMISSION_RATE,
            ]);

            // File uploads
            $cvPath = null;
            $degreePath = null;
            $videoPath = null;

            if ($request->hasFile('cv')) {
                $cvName = time() . '_' . $request->file('cv')->getClientOriginalName();
                $dest = public_path('storage/educators/cvs');
                if (!File::exists($dest)) File::makeDirectory($dest, 0755, true);
                $request->file('cv')->move($dest, $cvName);
                $cvPath = 'storage/educators/cvs/' . $cvName;
            }

            if ($request->hasFile('degree_proof')) {
                $degreeName = time() . '_' . $request->file('degree_proof')->getClientOriginalName();
                $dest = public_path('storage/educators/degrees');
                if (!File::exists($dest)) File::makeDirectory($dest, 0755, true);
                $request->file('degree_proof')->move($dest, $degreeName);
                $degreePath = 'storage/educators/degrees/' . $degreeName;
            }

            if ($request->hasFile('intro_video')) {
                $videoName = time() . '_' . $request->file('intro_video')->getClientOriginalName();
                $dest = public_path('storage/educators/videos');
                if (!File::exists($dest)) File::makeDirectory($dest, 0755, true);
                $request->file('intro_video')->move($dest, $videoName);
                $videoPath = 'storage/educators/videos/' . $videoName;
            }

            // Create educator profile
            EducatorProfile::create([
                'user_id'                  => $user->id,
                'primary_subject'          => $request->primary_subject,
                'educator_type'            => $request->educator_type,
                'teaching_levels'          => json_encode($request->teaching_levels),
                'hourly_rate'              => $request->hourly_rate,
                'certifications'           => $request->certifications,
                'preferred_teaching_style' => $request->preferred_teaching_style,
                'cv_path'                  => $cvPath,
                'degree_proof_path'        => $degreePath,
                'intro_video_path'         => $videoPath,
                'consent_verified'         => true,
                'status'                   => $request->status,
            ]);

            $this->storeAdditionalDocuments($request, $user->id);

            DB::commit();

            // Send login credentials email
            try {
                $loginUrl = url('/login');
                EmailService::send(
                    $user->email,
                    new EducatorCreatedMail($user->full_name, $user->email, $plainPassword, $loginUrl),
                    'emails'
                );
            } catch (\Exception $e) {
                Log::error('Failed to send educator created email: ' . $e->getMessage());
            }

            return redirect()->route('admin.educators.show', $user->id)
                ->with('success', 'Educator account created successfully. Login details have been emailed.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create educator: ' . $e->getMessage())->withInput();
        }
    }

    // ───────────────────────────────────────
    //  Edit educator form
    // ───────────────────────────────────────
    public function edit($id)
    {
        $educator = User::where('role', 'educator')
            ->with(['educatorProfile', 'additionalDocuments'])
            ->findOrFail($id);

        return view('admin.educator.edit', compact('educator'));
    }

    // ───────────────────────────────────────
    //  Update educator
    // ───────────────────────────────────────
    public function update(Request $request, $id)
    {
        $educator = User::where('role', 'educator')->findOrFail($id);

        $request->validate([
            'first_name'               => 'required|string|max:255',
            'last_name'                => 'required|string|max:255',
            'email'                    => 'required|email|unique:users,email,' . $educator->id,
            'primary_subject'          => 'required|string|max:255',
            'educator_type'            => 'nullable|in:teacher,tutor',
            'teaching_levels'          => 'required|array',
            'hourly_rate'              => 'required|numeric|min:5',
            'certifications'           => 'nullable|string',
            'preferred_teaching_style' => 'nullable|string|max:255',
            'cv'                       => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,pdf|max:6000',
            'degree_proof'             => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,pdf|max:6000',
            'additional_documents'              => 'nullable|array|max:5',
            'additional_documents.*'            => 'file|mimes:jpeg,png,jpg,gif,webp,pdf|max:6000',
            'replace_additional_documents'      => 'nullable|array',
            'replace_additional_documents.*'    => 'file|mimes:jpeg,png,jpg,gif,webp,pdf|max:6000',
            'remove_additional_documents'       => 'nullable|array',
            'remove_additional_documents.*'     => 'integer|exists:educator_additional_documents,id',
            'intro_video'              => 'nullable|file|mimetypes:video/mp4,video/quicktime|max:51200',
            'status'                   => 'required|in:pending,approved,rejected',
            'commission_rate'          => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Update user
            $educator->update([
                'first_name'      => $request->first_name,
                'last_name'       => $request->last_name,
                'email'           => $request->email,
                'commission_rate' => $request->filled('commission_rate')
                    ? $request->commission_rate
                    : User::DEFAULT_COMMISSION_RATE,
            ]);

            $profile = $educator->educatorProfile;
            if (!$profile) {
                $profile = new EducatorProfile(['user_id' => $educator->id]);
            }

            // File uploads (replace if new file provided)
            if ($request->hasFile('cv')) {
                $cvName = time() . '_' . $request->file('cv')->getClientOriginalName();
                $dest = public_path('storage/educators/cvs');
                if (!File::exists($dest)) File::makeDirectory($dest, 0755, true);
                $request->file('cv')->move($dest, $cvName);
                $profile->cv_path = 'storage/educators/cvs/' . $cvName;
            }

            if ($request->hasFile('degree_proof')) {
                $degreeName = time() . '_' . $request->file('degree_proof')->getClientOriginalName();
                $dest = public_path('storage/educators/degrees');
                if (!File::exists($dest)) File::makeDirectory($dest, 0755, true);
                $request->file('degree_proof')->move($dest, $degreeName);
                $profile->degree_proof_path = 'storage/educators/degrees/' . $degreeName;
            }

            if ($request->hasFile('intro_video')) {
                $videoName = time() . '_' . $request->file('intro_video')->getClientOriginalName();
                $dest = public_path('storage/educators/videos');
                if (!File::exists($dest)) File::makeDirectory($dest, 0755, true);
                $request->file('intro_video')->move($dest, $videoName);
                $profile->intro_video_path = 'storage/educators/videos/' . $videoName;
            }

            $profile->primary_subject          = $request->primary_subject;
            $profile->educator_type            = $request->educator_type;
            $profile->teaching_levels           = json_encode($request->teaching_levels);
            $profile->hourly_rate               = $request->hourly_rate;
            $profile->certifications            = $request->certifications;
            $profile->preferred_teaching_style   = $request->preferred_teaching_style;
            $profile->status                    = $request->status;
            $profile->save();

            $this->replaceAdditionalDocuments($request, $educator->id);
            $this->removeAdditionalDocuments($request, $educator->id);
            $this->storeAdditionalDocuments($request, $educator->id);

            DB::commit();

            return redirect()->route('admin.educators.show', $educator->id)
                ->with('success', 'Educator updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update educator: ' . $e->getMessage())->withInput();
        }
    }

    // ───────────────────────────────────────
    //  Send note / email to educator
    // ───────────────────────────────────────
    public function sendNote(Request $request, $id)
    {
        $educator = User::where('role', 'educator')->findOrFail($id);

        $request->validate([
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
        ]);

        try {
            EmailService::send(
                $educator->email,
                new EducatorNoteMail($educator->full_name, $request->subject, $request->body),
                'emails'
            );

            return back()->with('success', 'Email sent to ' . $educator->full_name . ' successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to send note to educator: ' . $e->getMessage());
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    // ───────────────────────────────────────
    //  Existing sub-pages
    // ───────────────────────────────────────
    public function payouts($id)
    {
        $educator = User::where('role', 'educator')->findOrFail($id);
        $payouts = Payment::where('educator_id', $id)
            ->with(['student', 'course'])
            ->latest()
            ->paginate(15);

        return view('admin.educator.payouts', compact('educator', 'payouts'));
    }

    public function courses($id)
    {
        $educator = User::where('role', 'educator')->findOrFail($id);
        $courses = $educator->courses()->with('category')->latest()->paginate(15);

        return view('admin.educator.courses', compact('educator', 'courses'));
    }

    public function earnings($id)
    {
        $educator = User::where('role', 'educator')->findOrFail($id);
        $earnings = Earning::where('educator_id', $id)
            ->with('course')
            ->latest()
            ->paginate(15);

        return view('admin.educator.earnings', compact('educator', 'earnings'));
    }

    public function sessions($id)
    {
        $educator = User::where('role', 'educator')->findOrFail($id);
        $sessions = SessionCall::where('educator_id', $id)
            ->with('students')
            ->latest()
            ->paginate(15);

        return view('admin.educator.sessions', compact('educator', 'sessions'));
    }

    private function replaceAdditionalDocuments(Request $request, int $educatorId): void
    {
        if (! $request->hasFile('replace_additional_documents')) {
            return;
        }

        $dest = public_path('storage/educators/additional_documents');
        if (! File::exists($dest)) {
            File::makeDirectory($dest, 0755, true);
        }

        foreach ($request->file('replace_additional_documents') as $docId => $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }

            $document = EducatorAdditionalDocument::where('educator_id', $educatorId)->find($docId);
            if (! $document) {
                continue;
            }

            if ($document->document_path && File::exists(public_path($document->document_path))) {
                File::delete(public_path($document->document_path));
            }

            $safeName = $educatorId . '_' . uniqid('', true) . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move($dest, $safeName);

            $document->update([
                'document_path' => 'storage/educators/additional_documents/' . $safeName,
                'document_type' => $file->getClientMimeType(),
                'document_name' => $file->getClientOriginalName(),
                'document_size' => (string) $file->getSize(),
            ]);
        }
    }

    private function storeAdditionalDocuments(Request $request, int $educatorId): void
    {
        if (! $request->hasFile('additional_documents')) {
            return;
        }

        $dest = public_path('storage/educators/additional_documents');
        if (! File::exists($dest)) {
            File::makeDirectory($dest, 0755, true);
        }

        foreach ($request->file('additional_documents') as $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }

            $safeName = $educatorId . '_' . uniqid('', true) . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move($dest, $safeName);

            EducatorAdditionalDocument::create([
                'educator_id'   => $educatorId,
                'document_path' => 'storage/educators/additional_documents/' . $safeName,
                'document_type' => $file->getClientMimeType(),
                'document_name' => $file->getClientOriginalName(),
                'document_size' => (string) $file->getSize(),
            ]);
        }
    }

    private function removeAdditionalDocuments(Request $request, int $educatorId): void
    {
        $ids = $request->input('remove_additional_documents', []);
        if (empty($ids)) {
            return;
        }

        EducatorAdditionalDocument::where('educator_id', $educatorId)
            ->whereIn('id', $ids)
            ->get()
            ->each(function (EducatorAdditionalDocument $document) {
                if ($document->document_path && File::exists(public_path($document->document_path))) {
                    File::delete(public_path($document->document_path));
                }
                $document->delete();
            });
    }
}
