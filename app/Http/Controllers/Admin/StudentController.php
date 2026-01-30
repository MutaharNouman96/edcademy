<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use App\Models\SessionCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function show($id)
    {
        $student = User::where('role', 'student')
                      ->with(['studentProfile', 'purchasedCourses'])
                      ->findOrFail($id);

        return view('admin.students.show', compact('student'));
    }

    public function courses($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        $courses = $student->purchasedCourses()->with('purchasable.educator')->latest()->paginate(15);
         

        return view('admin.students.courses', compact('student', 'courses'));
    }

    public function payments($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        $payments = Payment::where('student_id', $id)
                          ->with('educator', 'course')
                          ->latest()
                          ->paginate(15);

        return view('admin.students.payments', compact('student', 'payments'));
    }

    public function sessions($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        $sessions = SessionCall::whereHas('students', function($query) use ($id) {
            $query->where('user_id', $id);
        })->with('educator')->latest()->paginate(15);

        return view('admin.students.sessions', compact('student', 'sessions'));
    }

    public function getSessionDetails($studentId, $sessionId)
    {
        $session = SessionCall::whereHas('students', function($query) use ($studentId) {
            $query->where('user_id', $studentId);
        })->with('educator', 'course', 'students')->findOrFail($sessionId);

        $html = view('admin.partials.session-details', compact('session'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    public function resetPassword($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);

        // Generate a new random password
        $newPassword = Str::random(12);
        $student->password = Hash::make($newPassword);
        $student->save();

        // Send password reset email (you might want to create a specific email for this)
        // For now, we'll just return success
        // You can implement email sending here

        return response()->json(['success' => true, 'message' => 'Password reset successfully']);
    }

    public function activityLogs($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);

        // Get activity logs - you might need to implement logging system
        // For now, we'll show basic information
        $logs = [
            ['action' => 'Account Created', 'date' => $student->created_at->format('M d, Y H:i'), 'details' => 'Student account was created'],
            ['action' => 'Email Verified', 'date' => $student->email_verified_at ? $student->email_verified_at->format('M d, Y H:i') : 'N/A', 'details' => 'Email address was verified'],
            ['action' => 'Last Login', 'date' => $student->last_login_at ? $student->last_login_at->format('M d, Y H:i') : 'Never', 'details' => 'Last login to the system'],
        ];

        return view('admin.students.activity-logs', compact('student', 'logs'));
    }

    public function destroy($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);

        // You might want to add soft deletes or handle related data
        // For now, we'll just delete the user
        $student->delete();

        return redirect()->route('admin.manage.students')->with('success', 'Student deleted successfully');
    }

}