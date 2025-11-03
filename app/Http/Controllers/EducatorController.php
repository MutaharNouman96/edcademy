<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EducatorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EducatorController extends Controller
{
    public function create()
    {
        return view('educator.become_tutor');
    }

    public function store(Request $request)
    {
        $request->validate([
            // Step 1
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|confirmed|min:6',

            // Step 2
            'primary_subject' => 'required|string|max:255',
            'teaching_levels' => 'required|array',
            'hourly_rate'     => 'required|numeric|min:5',
            'certifications'  => 'nullable|string',
            'preferred_teaching_style' => 'nullable|string|max:255',

            // Step 3
            'govt_id'      => 'required|file|mimes:jpeg,png,pdf|max:2048',
            'degree_proof' => 'required|file|mimes:jpeg,png,pdf|max:2048',
            'intro_video'  => 'nullable|file|mimetypes:video/mp4,video/mov|max:51200', // 50MB
            'consent'      => 'accepted',
        ]);

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'first_name'     => $request->first_name,
                'last_name' => $request->last_name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'educator',
            ]);

            // File uploads
            if ($request->hasFile('govt_id')) {
                $govtIdName = time() . '_' . $request->file('govt_id')->getClientOriginalName();
                $request->file('govt_id')->move(public_path('storage/educators/ids'), $govtIdName);
                $govtIdPath = 'storage/educators/ids/' . $govtIdName;
            }

            if ($request->hasFile('degree_proof')) {
                $degreeName = time() . '_' . $request->file('degree_proof')->getClientOriginalName();
                $request->file('degree_proof')->move(public_path('storage/educators/degrees'), $degreeName);
                $degreePath = 'storage/educators/degrees/' . $degreeName;
            }

            $videoPath = null;
            if ($request->hasFile('intro_video')) {
                $videoName = time() . '_' . $request->file('intro_video')->getClientOriginalName();
                $request->file('intro_video')->move(public_path('storage/educators/videos'), $videoName);
                $videoPath = 'storage/educators/videos/' . $videoName;
            }

            // Create educator profile
            EducatorProfile::insert([
                'user_id' => $user->id,
                'primary_subject' => $request->primary_subject,
                'teaching_levels' => json_encode($request->teaching_levels),
                'hourly_rate' => $request->hourly_rate,
                'certifications' => $request->certifications,
                'preferred_teaching_style' => json_encode($request->preferred_teaching_style),
                'govt_id_path' => $govtIdPath,
                'degree_proof_path' => $degreePath,
                'intro_video_path' => $videoPath,
                'consent_verified' => true,
                'status' => 'pending',
            ]);

            DB::commit();

            // Send email
            // $user->sendEmailVerificationNotification();
            //login the user
            auth()->login($user);

            return redirect()->route('educator.dashboard')->with('success', 'Your application has been submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return back()->with('error', 'An error occurred while submitting.')->withInput();
        }
    }
}
