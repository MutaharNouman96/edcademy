<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\EducatorSessionSchedule;
use App\Models\EducatorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SessionScheduleController extends Controller
{
    public function index()
    {
        $educatorId = auth()->id();
        $schedules = EducatorSessionSchedule::where('educator_id', $educatorId)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        $profile = EducatorProfile::where('user_id', $educatorId)->first();
        $maxSessionsPerDay = $profile->max_sessions_per_day ?? 6;

        return view('crm.educator.session-schedule.index', compact('schedules', 'maxSessionsPerDay'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day_of_week' => 'required|integer|between:1,7',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $educatorId = auth()->id();

        EducatorSessionSchedule::create([
            'educator_id'  => $educatorId,
            'day_of_week'  => (int) $request->day_of_week,
            'start_time'   => $request->start_time,
            'end_time'     => $request->end_time,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Time slot added.']);
        }
        return redirect()->route('educator.session-schedule.index')->with('success', 'Time slot added.');
    }

    public function destroy(Request $request, $id)
    {
        $slot = EducatorSessionSchedule::where('id', $id)->where('educator_id', auth()->id())->firstOrFail();
        $slot->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Slot removed.']);
        }
        return redirect()->route('educator.session-schedule.index')->with('success', 'Slot removed.');
    }

    public function updateMaxSessions(Request $request)
    {
        $request->validate(['max_sessions_per_day' => 'required|integer|min:1|max:20']);
        $profile = EducatorProfile::where('user_id', auth()->id())->first();
        if (!$profile) {
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => 'Educator profile not found.'], 404)
                : redirect()->route('educator.session-schedule.index')->with('error', 'Educator profile not found.');
        }
        $profile->max_sessions_per_day = (int) $request->max_sessions_per_day;
        $profile->save();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Max sessions per day updated.']);
        }
        return redirect()->route('educator.session-schedule.index')->with('success', 'Max sessions per day updated.');
    }
}
