<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        return view('educator.dashboard');
    }

    public function profile(){
        $educator = auth()->user()->educatorProfile;
        return view('educator.profile' , compact('educator'));
    }


    public function profile_update(Request $request)
    {
        $user = auth()->user();
        $educator = $user->educatorProfile;

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'primary_subject' => 'required|string|max:255',
            'hourly_rate' => 'required|numeric|min:5',
            'teaching_levels' => 'nullable|array',
            'certifications' => 'nullable|string',
            'preferred_teaching_style' => 'nullable|string|max:255',
            'govt_id' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'degree_proof' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'intro_video' => 'nullable|file|mimetypes:video/mp4,video/mov|max:51200',
        ]);

        // Update user info
        $user->update(['first_name' => $request->first_name, 'last_name' => $request->last_name, 'email' => $request->email]);

        // Move uploaded files to public/storage manually
        $paths = [];
        if ($request->hasFile('govt_id')) {
            $file = $request->file('govt_id');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/educators/ids'), $filename);
            $paths['govt_id_path'] = 'educators/ids/' . $filename;
        }

        if ($request->hasFile('degree_proof')) {
            $file = $request->file('degree_proof');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/educators/degrees'), $filename);
            $paths['degree_proof_path'] = 'educators/degrees/' . $filename;
        }

        if ($request->hasFile('intro_video')) {
            $file = $request->file('intro_video');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/educators/videos'), $filename);
            $paths['intro_video_path'] = 'educators/videos/' . $filename;
        }

        $educator->update(array_merge([
            'primary_subject' => $request->primary_subject,
            'hourly_rate' => $request->hourly_rate,
            'teaching_levels' => $request->teaching_levels,
            'certifications' => $request->certifications,
            'preferred_teaching_style' => $request->preferred_teaching_style,
        ], $paths));

        return back()->with('success', 'Profile updated successfully!');
    }
}
