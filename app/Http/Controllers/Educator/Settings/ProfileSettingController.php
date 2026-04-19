<?php

namespace App\Http\Controllers\Educator\Settings;

use App\Http\Controllers\Controller;
use App\Models\EducatorProfile;
use App\Models\EducatorSessionSchedule;
use Illuminate\Http\Request;

class ProfileSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = auth()->user();

        // Fetch all settings via relationships
        $profile        = $user->profileSetting;
        $security       = $user->securitySetting;
        $payment        = $user->paymentSetting;
        $methods        = $user->paymentMethods;
        $availability   = $user->availability;
        $notifications  = $user->notificationSetting;
        $privacy        = $user->privacy;
        $verification   = $user->verification;
        $connections    = $user->connections;
        $preferences    = $user->preferences;

        $sessionSchedules = $user->sessionSchedules()->orderBy('day_of_week')->orderBy('start_time')->get();
        $educatorProfile  = EducatorProfile::where('user_id', $user->id)->first();
        $maxSessionsPerDay = $educatorProfile->max_sessions_per_day ?? 6;
        $additionalDocuments = $user->additionalDocuments()->latest()->get();

        return view('educator.settings', compact(
            'user',
            'profile',
            'security',
            'payment',
            'methods',
            'availability',
            'notifications',
            'privacy',
            'verification',
            'connections',
            'preferences',
            'sessionSchedules',
            'maxSessionsPerDay',
            'additionalDocuments'
        ));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'first_name'               => 'required|string|max:100',
            'last_name'                => 'required|string|max:100',
            'handle'                   => 'required|string|max:50|unique:users,username,' . $user->id,
            'bio'                      => 'nullable|string|max:2000',
            'subjects'                 => 'nullable|array',
            'rate'                     => 'nullable|numeric|min:0',
            'teaching_levels'          => 'nullable|array',
            'certifications'           => 'nullable|string|max:5000',
            'preferred_teaching_style' => 'nullable|string|max:255',
            'intro_video_path'         => 'nullable|file|mimes:mp4,mov,avi,wmv|max:51200',
            'avatar'                   => 'nullable|image|max:2048',
        ]);
 
        /* ============================
         | USER BASIC DATA
         ============================*/
        $user->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'username'   => $request->handle,
            'bio'        => $request->bio,
        ]);

        /* ============================
         | AVATAR UPLOAD
         ============================*/
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
               $path = public_path( $user->avatar);
               unlink($path);
            }

            $filename = time() . '_' . $request->file('avatar')->getClientOriginalName();
            $request->file('avatar')->move(public_path('storage/avatars'), $filename);
            $user->profile_picture = 'storage/avatars/' . $filename;
            $user->save();
        }

        /* ============================
         | EDUCATOR PROFILE
         ============================*/
        $profileData = [
            'primary_subject'          => $request->subjects ? json_encode($request->subjects) : null,
            'hourly_rate'              => $request->rate,
            'teaching_levels'          => $request->teaching_levels ? json_encode($request->teaching_levels) : null,
            'certifications'           => $request->certifications,
            'preferred_teaching_style' => $request->preferred_teaching_style,
        ];

        if ($request->hasFile('intro_video_path')) {
            $profile = EducatorProfile::where('user_id', $user->id)->first();
            if ($profile && $profile->intro_video_path) {
                $oldPath = storage_path('app/public/' . $profile->intro_video_path);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $filename = time() . '_' . $request->file('intro_video_path')->getClientOriginalName();
            $request->file('intro_video_path')->move(storage_path('app/public/educator_videos'), $filename);
            $profileData['intro_video_path'] = 'educator_videos/' . $filename;
        }

        EducatorProfile::updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return response()->json([
            'status'  => true,
            'message' => 'Profile updated successfully'
        ]);
    }
}
