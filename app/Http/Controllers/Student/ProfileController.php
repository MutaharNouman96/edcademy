<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the student's profile form.
     */
    public function edit(): View
    {
        $student_profile = auth()->user()->studentProfile;
        $user = auth()->user();

        // dd($student_profile);
        return view('student.profile.edit', compact('user', 'student_profile'));
    }

    /**
     * Update the student's profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->user()->id],
            'avatar' => ['nullable', 'string', 'max:255'], // Add validation for avatar
        ]);

        $user = auth()->user();

        if ($user->guardian) {
            $request->validate([
                'guardian_name' => ['required', 'string', 'max:255'],
                'guardian_relation' => ['required', 'string', 'max:255'],
                'guardian_contact' => ['required', 'string', 'max:255'],
            ]);
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->avatar = $request->avatar; // Save the selected avatar URL
        $user->save();

        if ($user->guardian) {
            $user->guardian->update([
                'guardian_name' => $request->guardian_name,
                'guardian_contact' => $request->guardian_contact,
                'guardian_relation' => $request->guardian_relation,
            ]);
        }

        return redirect()->route('student.profile.edit')->with('status', 'profile-updated');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'string', 'url', 'max:255'],
        ]);

        $user = auth()->user();
        $user->profile_picture = $request->avatar;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Avatar updated successfully.']);
    }

    public function updateProfile(Request $request)
{
    $user = auth()->user(); // logged-in student

    DB::table('users')->where('id', $user->id)->update([
        'first_name' => $request->first_name,
        'last_name'  => $request->last_name,
        'email'      => $request->email,
    ]);

    // Update main student profile
    DB::table('student_profiles')->updateOrInsert(
        ['user_id' => $user->id],
        [
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'bio'        => $request->bio,
            'education_level'  => $request->education,
            'interests'  => $request->interests,
        ]
    );


    return response()->json(
        [
            'success' => true,
            'message' => 'Profile updated successfully!',
        ]
    );
}

    public function updateAccount(Request $request)
    {
        $request->validate([
            'phone' => ['nullable', 'string', 'max:255'],
            'language' => ['nullable', 'string', 'max:255'],
            'timezone' => ['nullable', 'string', 'max:255'],
        ]);

        $user = auth()->user();
        $user->studentProfile->update([
            'phone' => $request->phone,
            'language' => $request->language,
            'timezone' => $request->timezone,
        ]);

        return response()->json(['success' => true, 'message' => 'Account settings updated successfully!']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();
        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['success' => true, 'message' => 'Password updated successfully!']);
    }

    public function updateNotifications(Request $request)
    {
        $request->validate([
            'notify_email' => ['boolean'],
            'notify_sms' => ['boolean'],
            'notify_push' => ['boolean'],
            'notify_in_app' => ['boolean'],
            'evt_course_release' => ['boolean'],
            'evt_assignment' => ['boolean'],
            'evt_promos' => ['boolean'],
            'evt_system' => ['boolean'],
            'digest' => ['nullable', 'string', 'in:daily,weekly,monthly'],
        ]);

        $user = auth()->user();
        $notificationSettings = $user->notificationSetting()->firstOrCreate(['user_id' => $user->id]);

        $channels = $notificationSettings->channels ?? [];
        $events = $notificationSettings->events ?? [];

        // Update channels
        $channels['email'] = $request->has('notify_email');
        $channels['sms'] = $request->has('notify_sms');
        $channels['push'] = $request->has('notify_push');
        $channels['in_app'] = $request->has('notify_in_app');

        // Update events
        $events['course_release'] = $request->has('evt_course_release');
        $events['assignment'] = $request->has('evt_assignment');
        $events['promos'] = $request->has('evt_promos');
        $events['system'] = $request->has('evt_system');

        $notificationSettings->update([
            'channels' => $channels,
            'events' => $events,
            'digest' => $request->input('digest', 'weekly'),
        ]);

        return response()->json(['success' => true, 'message' => 'Notification settings updated successfully!']);
    }
}
