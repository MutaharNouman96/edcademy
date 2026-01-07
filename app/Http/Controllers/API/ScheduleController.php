<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $schedules = Schedule::where('user_id', $user->id)
            ->with('session:id,title,status')
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'title' => $s->title,
                    'start' => $s->start_date?->toIso8601String(),
                    'end' => $s->end_date?->toIso8601String(),
                    'color' => $this->colorForSession($s->session),
                    'description' => $s->description,
                    'session_id' => $s->session_id,
                    'session' => $s->session ? [
                        'id' => $s->session->id,
                        'title' => $s->session->title,
                        'status' => $s->session->status,
                    ] : null,
                ];
            });

        return response()->json($schedules);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $v = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'session_id' => 'nullable|exists:session_calls,id',
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $schedule = Schedule::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start,
            'end_date' => $request->end ?? $request->start,
            'session_id' => $request->session_id,
        ]);

        $schedule->load('session');

        return response()->json([
            'id' => $schedule->id,
            'title' => $schedule->title,
            'start' => $schedule->start_date->toIso8601String(),
            'end' => $schedule->end_date->toIso8601String(),
            'color' => $this->colorForSession($schedule->session),
            'description' => $schedule->description,
            'session_id' => $schedule->session_id,
        ], 201);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $user = $request->user();

        // Authorization: user must own this schedule
        if ($schedule->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $v = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'sometimes|required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'session_id' => 'nullable|exists:sessions,id',
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        if ($request->has('title')) $schedule->title = $request->title;
        if ($request->has('description')) $schedule->description = $request->description;
        if ($request->has('start')) $schedule->start_date = $request->start;
        if ($request->has('end')) $schedule->end_date = $request->end ?? $request->start;
        if ($request->has('session_id')) $schedule->session_id = $request->session_id;

        $schedule->save();
        $schedule->load('session');

        return response()->json([
            'id' => $schedule->id,
            'title' => $schedule->title,
            'start' => $schedule->start_date?->toIso8601String(),
            'end' => $schedule->end_date?->toIso8601String(),
            'color' => $this->colorForSession($schedule->session),
            'description' => $schedule->description,
            'session_id' => $schedule->session_id,
        ]);
    }

    public function destroy(Request $request, Schedule $schedule)
    {
        $user = $request->user();

        if ($schedule->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $schedule->delete();

        return response()->json(['message' => 'Deleted'], 200);
    }

    // Map session status to color (customize)
    protected function colorForSession($session)
    {
        if (! $session) {
            return '#3f51b5'; // default
        }

        return match ($session->status ?? 'scheduled') {
            'draft' => '#9E9E9E',
            'scheduled' => '#4CAF50',
            'completed' => '#607D8B',
            'cancelled' => '#D32F2F',
            default => '#3f51b5',
        };
    }
}
