<?php

namespace App\Http\Controllers\Educator\Settings;

use App\Http\Controllers\Controller;
use App\Models\EducatorProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileSettingController extends Controller
{
    private const SUBJECTS = [
        'Mathematics',
        'Science',
        'English',
        'Computer Science',
        'Languages',
        'Other',
    ];

    private const TEACHING_LEVELS = [
        'Elementary',
        'Middle School',
        'High School',
        'College',
        'Professional',
    ];

    private const TEACHING_STYLES = [
        'Interactive / Discussion-based',
        'Lecture / Presentation',
        'Hands-on / Practical',
        'Assessment-driven',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

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
            'subjects'                 => 'nullable|array|min:1',
            'subjects.*'               => ['string', Rule::in(self::SUBJECTS)],
            'rate'                     => 'nullable|numeric|min:0',
            'teaching_levels'          => 'nullable|array|min:1',
            'teaching_levels.*'        => ['string', Rule::in(self::TEACHING_LEVELS)],
            'certifications'           => 'nullable|string|max:5000',
            'preferred_teaching_style' => ['nullable', 'string', Rule::in(self::TEACHING_STYLES)],
            'avatar'                   => 'nullable|image|max:2048',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'username'   => $request->handle,
            'bio'        => $request->bio,
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                $path = public_path($user->avatar);
                if (is_file($path)) {
                    @unlink($path);
                }
            }

            $filename = time() . '_' . $request->file('avatar')->getClientOriginalName();
            $request->file('avatar')->move(public_path('storage/avatars'), $filename);
            $user->profile_picture = 'storage/avatars/' . $filename;
            $user->save();
        }

        EducatorProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'primary_subject'          => $request->subjects ? implode(', ', $request->subjects) : null,
                'hourly_rate'              => $request->rate,
                'teaching_levels'          => $request->teaching_levels ? json_encode($request->teaching_levels) : null,
                'certifications'           => $request->certifications,
                'preferred_teaching_style' => $request->preferred_teaching_style,
            ]
        );

        return response()->json([
            'status'  => true,
            'message' => 'Profile updated successfully',
        ]);
    }
}
