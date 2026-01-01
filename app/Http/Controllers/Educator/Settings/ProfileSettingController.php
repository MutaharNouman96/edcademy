<?php

namespace App\Http\Controllers\Educator\Settings;

use App\Http\Controllers\Controller;
use App\Models\EducatorProfile;
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
            'preferences'
        ));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'handle'     => 'required|string|max:50|unique:users,username,' . $user->id,
            'headline'   => 'nullable|string|max:255',
            'bio'        => 'nullable|string|max:2000',
            'subjects'   => 'nullable|array',
            'languages'  => 'nullable',
            'rate'       => 'nullable|numeric|min:0',
            'location'   => 'nullable|string|max:255',
            'video_url'  => 'nullable|string|max:255',
            'website'    => 'nullable|string|max:255',
            'social'     => 'nullable|string|max:255',
            'avatar'     => 'nullable|image|max:2048'
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
        EducatorProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'primary_subject' => $request->subjects ? json_encode($request->subjects) : null,
                'hourly_rate'     => $request->rate,
                'intro_video_path' => $request->video_url,
                'preferred_teaching_style' => null,
                 
            ]
        );

        return response()->json([
            'status'  => true,
            'message' => 'Profile updated successfully'
        ]);
    }
}
