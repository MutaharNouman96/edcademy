<?php

namespace App\Http\Controllers\Educator\Settings;

use App\Http\Controllers\Controller;
use App\Models\EducatorProfile;
use App\Models\EducatorSessionSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AvailabilitySettingController extends Controller
{
    /**
     * Display the availability/schedule settings (handled by ProfileSettingController index).
     */
    public function index()
    {
        return redirect()->route('educator.settings')->withFragment('availability');
    }

    /**
     * Store session schedule (day + time slots) and max_sessions_per_day.
     * Expects: grid (array of 7 items: { active, start, end } for Mon-Sun), max_per_day (int).
     */
    public function update(Request $request)
    {
        $request->validate([
            'grid' => 'required|array',
            'max_per_day'  => 'required|integer|min:1|max:20',
        ]);

        foreach ($request->input('grid', []) as $i => $row) {
            $active = filter_var($row['active'] ?? false, FILTER_VALIDATE_BOOLEAN);
            if (!$active) continue;
            if (empty($row['start']) || empty($row['end'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Start and end time are required for each active day.',
                ], 422);
            }
            if (strtotime($row['end']) <= strtotime($row['start'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'End time must be after start time for each day.',
                ], 422);
            }
        }

        $user = auth()->user();
        $educatorId = $user->id;
        $grid = $request->input('grid', []);
        $maxPerDay = (int) $request->input('max_per_day', 6);

        DB::beginTransaction();
        try {
            EducatorSessionSchedule::where('educator_id', $educatorId)->delete();

            foreach ($grid as $i => $row) {
                $active = filter_var($row['active'] ?? false, FILTER_VALIDATE_BOOLEAN);
                if (!$active || empty($row['start']) || empty($row['end'])) {
                    continue;
                }
                $dayOfWeek = (int) $i + 1;
                if ($dayOfWeek < 1 || $dayOfWeek > 7) {
                    continue;
                }
                EducatorSessionSchedule::create([
                    'educator_id' => $educatorId,
                    'day_of_week' => $dayOfWeek,
                    'start_time'  => $row['start'],
                    'end_time'    => $row['end'],
                ]);
            }

            $profile = EducatorProfile::where('user_id', $educatorId)->first();
            if ($profile) {
                $profile->max_sessions_per_day = $maxPerDay;
                $profile->save();
            } else {
                EducatorProfile::create([
                    'user_id' => $educatorId,
                    'max_sessions_per_day' => $maxPerDay,
                    'hourly_rate' => 0,
                    'status' => 'pending',
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to save availability.',
                'error' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Availability saved.',
        ]);
    }
}
