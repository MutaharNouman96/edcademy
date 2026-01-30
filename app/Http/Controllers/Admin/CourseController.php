<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function approve(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $course->update([
            'approval_status' => 'approved',
            'review_note' => null // Clear any previous rejection note
        ]);

        return back()->with('success', 'Course approved successfully');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'review_note' => 'required|string|max:1000'
        ]);

        $course = Course::findOrFail($id);
        $course->update([
            'approval_status' => 'rejected',
            'review_note' => $request->review_note
        ]);

        return back()->with('success', 'Course rejected with note');
    }
}