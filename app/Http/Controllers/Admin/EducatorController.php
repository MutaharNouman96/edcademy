<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use App\Models\Earning;
use App\Models\SessionCall;
use Illuminate\Http\Request;

class EducatorController extends Controller
{
    public function show($id)
    {
        $educator = User::where('role', 'educator')
                       ->with(['educatorProfile', 'courses'])
                       ->findOrFail($id);

        return view('admin.educator.show', compact('educator'));
    }

    public function payouts($id)
    {
        $educator = User::where('role', 'educator')->findOrFail($id);
        $payouts = Payment::where('educator_id', $id)
                         ->with('student')
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
}