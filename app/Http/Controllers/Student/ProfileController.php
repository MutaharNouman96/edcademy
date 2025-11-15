<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the student's profile form.
     */
    public function edit(): View
    {
        return view('student.profile.edit', [
            'user' => auth()->user(),
        ]);
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
}
