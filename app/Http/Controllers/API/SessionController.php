<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SessionCall;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        // If educator, return their sessions; else empty
        $user = $request->user();

        $query = SessionCall::query();

        // If you have roles, ensure only educator's sessions are returned
        // Here we'll return sessions where educator_id = user->id OR all if admin
        if ($user) {
            $query->where('educator_id', $user->id);
        }

        $sessions = $query->select('id', 'title', 'start_time', 'end_time', 'status', 'is_paid', 'price')
            ->orderBy('start_time', 'asc')
            ->get();

        return response()->json($sessions);
    }
}
